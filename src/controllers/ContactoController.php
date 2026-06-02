<?php
session_start();
require_once __DIR__ . '/../models/solicitudes.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../config/mailer.php';
require_once __DIR__ . '/../models/tickets.php';
require_once __DIR__ . '/../models/usuario.php';

use PHPMailer\PHPMailer\Exception;

class ContactoController
{
    private SolicitudModel $solicitudModel;

    public function __construct($conn)
    {
        $this->solicitudModel = new SolicitudModel($conn);
    }

    //verifica POST sino devuelve 405
    public function enviarContacto(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            http_response_code(405);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            exit;
        }

        $nombre  = trim($_POST['nombre']  ?? '');
        $correo  = trim($_POST['email']   ?? '');
        $asunto  = trim($_POST['asunto']  ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        $idUsuario = $_SESSION['usuario']['id_usuario'] ?? null;

        // devuelve jSON con los errores
        $errores = $this->validar($nombre, $correo, $asunto, $mensaje);


        if (!empty($errores)) {
            http_response_code(422);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Por favor corrige los errores del formulario.',
                'errores' => $errores,
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Crea la solicitud para insertarlo en BD
        try {
            $idSolicitud = $this->solicitudModel->crear([
                'id_usuario' => $idUsuario,
                'nombre'     => $nombre,
                'correo'     => $correo,
                'asunto'     => $asunto,
                'mensaje'    => $mensaje,
            ]);
        } catch (PDOException $e) { //captura error en la inserccion
            error_log('Error BD: ' . $e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar la solicitud. Inténtalo de nuevo.' .  $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Enviar correo, si falla solo logea, no interrumpe el proceso
        $cuerpoHtml = $this->plantillaHTML($nombre, $correo, $asunto, $mensaje, $idSolicitud);
        $enviado    = enviarCorreo('finishlineheesni@gmail.com', '[FinishLine] Nuevo mensaje: ' . $this->etiquetaAsunto($asunto), $cuerpoHtml);

        if (!$enviado) {
            error_log('Error al enviar correo de contacto para solicitud #' . $idSolicitud);
        }

        //
        header('Location: /src/views/contacto.php?status=success');
        exit;
    }

    private function plantillaHTML(string $nombre, string $correo, string $asunto, string $mensaje, int $id): string
    {
        $asuntoLabel = htmlspecialchars($this->etiquetaAsunto($asunto)); //aseroria tecnica o otros
        $nombre      = htmlspecialchars($nombre);
        $correo      = htmlspecialchars($correo);
        $mensaje     = nl2br(htmlspecialchars($mensaje));

        //cuerpo de email
        return "
        <div style='font-family:Poppins,sans-serif;max-width:600px;margin:auto;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;'>
            <div style='background:#1a2744;padding:24px 32px;'>
                <h2 style='color:#00d4ff;margin:0;font-size:20px;'>FINISH<span style='color:#fff;'>LINE</span></h2>
                <p style='color:#a0aec0;margin:4px 0 0;font-size:13px;'>Nuevo mensaje desde el formulario de contacto</p>
            </div>
            <div style='padding:32px;background:#fff;'>
                <table style='width:100%;border-collapse:collapse;'>
                    <tr>
                        <td style='padding:10px 0;color:#718096;font-size:13px;width:120px;'>Nº Solicitud</td>
                        <td style='padding:10px 0;font-weight:600;color:#1a2744;'>#$id</td>
                    </tr>
                    <tr style='border-top:1px solid #edf2f7;'>
                        <td style='padding:10px 0;color:#718096;font-size:13px;'>Nombre</td>
                        <td style='padding:10px 0;font-weight:600;color:#1a2744;'>$nombre</td>
                    </tr>
                    <tr style='border-top:1px solid #edf2f7;'>
                        <td style='padding:10px 0;color:#718096;font-size:13px;'>Correo</td>
                        <td style='padding:10px 0;'><a href='mailto:$correo' style='color:#00d4ff;'>$correo</a></td>
                    </tr>
                    <tr style='border-top:1px solid #edf2f7;'>
                        <td style='padding:10px 0;color:#718096;font-size:13px;'>Asunto</td>
                        <td style='padding:10px 0;font-weight:600;color:#1a2744;'>$asuntoLabel</td>
                    </tr>
                    <tr style='border-top:1px solid #edf2f7;'>
                        <td style='padding:10px 0;color:#718096;font-size:13px;vertical-align:top;'>Mensaje</td>
                        <td style='padding:10px 0;color:#2d3748;line-height:1.6;'>$mensaje</td>
                    </tr>
                </table>
            </div>
            <div style='background:#f7fafc;padding:16px 32px;text-align:center;'>
                <p style='color:#a0aec0;font-size:12px;margin:0;'>FinishLine · Villaverde Bajo, Calle del Diamante 40, Madrid</p>
            </div>
        </div>";
    }

    private function etiquetaAsunto(string $value): string
    {
        return match ($value) {
            'tecnico'     => 'Asesoría Técnica',
            'otros'       => 'Otros',
            default       => $value,
        };
    }

    //Restricciones del formulario
    private function validar(string $nombre, string $correo, string $asunto, string $mensaje): array
    {
        $errores = [];

        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (strlen($nombre) < 2 || strlen($nombre) > 100) {
            $errores['nombre'] = 'El nombre debe tener entre 2 y 100 caracteres.';
        }

        if (empty($correo)) {
            $errores['correo'] = 'El correo electrónico es obligatorio.';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'El correo electrónico no es válido.';
        }

        $asuntosPermitidos = ['tecnico', 'informacion', 'otros'];
        if (empty($asunto) || !in_array($asunto, $asuntosPermitidos, true)) {
            $errores['asunto'] = 'Selecciona un asunto válido.';
        }

        if (empty($mensaje)) {
            $errores['mensaje'] = 'El mensaje es obligatorio.';
        } elseif (strlen($mensaje) < 10) {
            $errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres.';
        } elseif (strlen($mensaje) > 500) {
            $errores['mensaje'] = 'El mensaje no puede superar los 500 caracteres.';
        }

        return $errores;
    }
}

// Instancia al final para ctuar como controlador y punto de entrada
$database   = new Database();
$conn       = $database->getConnection();
$controller = new ContactoController($conn);
$controller->enviarContacto();
