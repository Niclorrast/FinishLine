<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/servicios.php';
require_once __DIR__ . '/../models/tickets.php';
require_once __DIR__ . '/../models/documentos.php';
require_once __DIR__ . '/../config/mailer.php';

use PHPMailer\PHPMailer\Exception;

// Redirige si no esta logeado
if (!isset($_SESSION['usuario'])) {
    header("Location: /src/views/login.php");
    exit();
}

$database = new Database();
$conn     = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $servicioModel  = new Servicio($conn);
    $listaServicios = $servicioModel->obtenerTodos();
    $servicio_id    = $_GET['servicio'] ?? '';
    $status         = $_GET['status']   ?? '';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_usuario  = (int)$_SESSION['usuario']['id'];
    $id_servicio = (int)($_POST['servicio']      ?? 0);
    $modelo      = trim($_POST['modelo_coche']   ?? '');
    $descripcion = trim($_POST['descripcion']    ?? '');
    $matricula = trim($_POST['matricula'] ?? '');

    $ticketModel = new Ticket($conn);//crear ticket pasandole la conexion
    $id_ticket   = $ticketModel->crear($id_usuario, $id_servicio, $modelo, $descripcion, $matricula);

    if ($id_ticket === 0) { 
        header("Location: /src/views/presupuesto.php?status=error");
        exit();
    }

    //Guardado de imagenes hacia la carpeta 
    $contenido_binarios = [];
    $tipos_mime         = [];
    $extensiones        = [];
    $rutas_imagenes     = [];
    $foto_nombres       = [];

    if (isset($_FILES['foto_vehiculo']) && is_array($_FILES['foto_vehiculo']['name'])) { //comprueba si llegan 
        $docModel   = new Documento($conn);
        $permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        foreach ($_FILES['foto_vehiculo']['error'] as $i => $error) { //COMPRUEBA MIME solo jpeg, png, webp y gif

            $finfo     = new finfo(FILEINFO_MIME_TYPE);
            $tipo_mime = $finfo->file($_FILES['foto_vehiculo']['tmp_name'][$i]);
            if (!in_array($tipo_mime, $permitidos)) continue;
            if ($_FILES['foto_vehiculo']['size'][$i] > 10 * 1024 * 1024) continue; //solo 10MB 

            $extension         = pathinfo($_FILES['foto_vehiculo']['name'][$i], PATHINFO_EXTENSION);
            $nombre_original   = $_FILES['foto_vehiculo']['name'][$i];
            $contenido_binario = file_get_contents($_FILES['foto_vehiculo']['tmp_name'][$i]);

            //guardado si cumple con todo lo anterior
            $ruta = $docModel->guardarDocumentoTicket($id_ticket, $nombre_original, $tipo_mime, $contenido_binario);

            if ($ruta) {
                $rutas_imagenes[] = $ruta; // guardar la ruta para el correo
                $foto_nombres[]   = $nombre_original;
            }
        }
    }

    //Envio correo
    
        $foto_status = count($foto_nombres) > 0
            ? "✅ " . count($foto_nombres) . " foto(s) adjuntada(s)"
            : "❌ No adjuntada";

        $stmtSrv         = $conn->prepare("SELECT Nombre FROM Servicios WHERE id_servicio = ?");
        $stmtSrv->execute([$id_servicio]);
        $servicio_nombre = $stmtSrv->fetchColumn() ?: 'No especificado';

        $nombre_safe      = htmlspecialchars($_SESSION['usuario']['nombre']);
        $modelo_safe      = htmlspecialchars($modelo);
        $descripcion_safe = htmlspecialchars($descripcion);

        $cuerpoHtml = "
        <div style='font-family:Poppins,sans-serif;max-width:600px;margin:0 auto;
                    border:1px solid #e2e8f0;border-radius:12px;overflow:hidden'>
            <div style='background:#0A2540;padding:24px 32px'>
                <h2 style='color:#00D4FF;margin:0'>Nueva Solicitud de Presupuesto</h2>
            </div>
            <div style='padding:32px;background:#F7FAFC'>
                <table style='width:100%'>
                    <tr><td>👤 Cliente:</td><td>{$nombre_safe}</td></tr>
                    <tr><td>🔧 Servicio:</td><td>{$servicio_nombre}</td></tr>
                    <tr><td>🚗 Vehículo:</td><td>{$modelo_safe}</td></tr>
                    <tr><td>📝 Descripción:</td><td>{$descripcion_safe}</td></tr>
                    <tr><td>📎 Foto:</td><td>{$foto_status}</td></tr>
                </table>
            </div>
        </div>";

        enviarCorreo('finishlineheesni@gmail.com', '📋 Nueva solicitud de presupuesto — FinishLine', $cuerpoHtml);
    

    header("Location: /src/views/presupuesto.php?status=success");

    exit();
}
