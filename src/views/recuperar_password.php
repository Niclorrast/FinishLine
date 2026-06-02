<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../config/mailer.php';

use PHPMailer\PHPMailer\Exception;

$database = new Database();
$conn     = $database->getConnection();

$mensaje = '';
$tipo    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');

    if (empty($correo)) {
        $mensaje = 'Por favor, introduce tu correo electrónico.';
        $tipo    = 'error';
    } else {
        $stmt = $conn->prepare("SELECT id_usuario FROM Usuario WHERE Correo = ?");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        // Siempre mostramos el mismo mensaje (no revelar si el correo existe)
        $mensaje = 'Si el correo está registrado, recibirás un enlace en breve. Revisa también el spam.';
        $tipo    = 'ok';

        if ($usuario) {
            // Generar token y guardarlo en BD
            $token  = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', time() + 3600); // 1 hora

            $stmt = $conn->prepare("DELETE FROM password_reset WHERE id_usuario = ?");
            $stmt->execute([$usuario['id_usuario']]);

            $stmt = $conn->prepare(
                "INSERT INTO password_reset (id_usuario, token, expira_en) VALUES (?, ?, ?)"
            );
            $stmt->execute([$usuario['id_usuario'], $token, $expira]);

            $enlace = "http://" . $_SERVER['HTTP_HOST'] . "/src/views/restablecer_password.php?token=" . $token;

            // Enviar email con PHPMailer
            try {
                $cuerpoHtml = "
                <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
                    <h2 style='color:#0A2540;'>FinishLine</h2>
                    <p>Hola,</p>
                    <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                    <p>Haz clic en el botón para crear una nueva (el enlace caduca en <strong>1 hora</strong>):</p>
                    <a href='{$enlace}'
                    style='display:inline-block;padding:12px 24px;background:#0A2540;
                            color:#ffffff;text-decoration:none;border-radius:5px;
                            font-weight:bold;margin:16px 0;'>
                        Restablecer contraseña
                    </a>
                    <p style='color:#999;font-size:0.85rem;'>
                        Si no has solicitado esto, ignora este mensaje.<br>
                        O copia este enlace en tu navegador:<br>
                        <a href='{$enlace}' style='color:#007bff;'>{$enlace}</a>
                    </p>
                    <hr style='border:none;border-top:1px solid #eee;margin-top:24px;'>
                    <p style='color:#aaa;font-size:0.8rem;'>FinishLine — Chapa y Pintura</p>
                </div>
            ";

            $enviado = enviarCorreo($correo, 'FinishLine - Recuperación de contraseña', $cuerpoHtml);
            if (!$enviado) {
                error_log("Error al enviar email recuperación a: {$correo}");
            }
            } catch (Exception $e) {
                // No mostramos el error al usuario por seguridad,
                // pero lo podemos ver en logs si hace falta
                error_log("Error al enviar email recuperación: " . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Recuperar contraseña</title>
    <link rel="stylesheet" href="/src/styles/login.css">
    <style>
        .ok-mensaje {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border: 1px solid #a5d6a7;
        }
        .back-link { margin-top: 15px; font-size: 0.9rem; }
        .back-link a { color: #007bff; text-decoration: none; font-weight: bold; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>FinishLine</h1>
        <p class="welcome-text">
            Introduce tu correo y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <?php if ($mensaje && $tipo === 'error'): ?>
            <div class="error-mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php elseif ($mensaje && $tipo === 'ok'): ?>
            <div class="ok-mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($tipo !== 'ok'): ?>
        <form method="POST" action="">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <button type="submit">Enviar enlace</button>
        </form>
        <?php endif; ?>

        <p class="back-link">
            <a href="/src/views/login.php">← Volver al inicio de sesión</a>
        </p>
    </div>
</body>
</html>
