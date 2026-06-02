<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';

$database = new Database();
$conn     = $database->getConnection();

$mensaje      = '';
$tipo         = '';
$token_valido = false;
$token        = trim($_GET['token'] ?? $_POST['token'] ?? '');

// Verificar token
if (!empty($token)) {
    $stmt = $conn->prepare(
        "SELECT pr.*, u.Correo
         FROM password_reset pr
         JOIN Usuario u ON pr.id_usuario = u.id_usuario
         WHERE pr.token = ? AND pr.expira_en > NOW()"
    );
    $stmt->execute([$token]);
    $registro = $stmt->fetch();

    if ($registro) {
        $token_valido = true;
    } else {
        $mensaje = 'El enlace no es válido o ha caducado. Solicita uno nuevo.';
        $tipo    = 'error';
    }
} else {
    $mensaje = 'Enlace incorrecto. Solicita uno nuevo.';
    $tipo    = 'error';
}

// Procesar nueva contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valido) {
    $nueva     = trim($_POST['password']  ?? '');
    $confirmar = trim($_POST['confirmar'] ?? '');

    if (empty($nueva) || empty($confirmar)) {
        $mensaje = 'Rellena los dos campos.';
        $tipo    = 'error';
    } elseif (strlen($nueva) < 6) {
        $mensaje = 'La contraseña debe tener al menos 6 caracteres.';
        $tipo    = 'error';
    } elseif ($nueva !== $confirmar) {
        $mensaje = 'Las contraseñas no coinciden.';
        $tipo    = 'error';
    } else {
        $hash = password_hash($nueva, PASSWORD_BCRYPT);

        // Actualizar en tabla Autenticacion (unida a Usuario)
        $stmt = $conn->prepare(
            "UPDATE Autenticacion a
             JOIN Usuario u ON u.id_autenticacion = a.id_autenticacion
             SET a.password_hash = ?
             WHERE u.id_usuario = ?"
        );
        $stmt->execute([$hash, $registro['id_usuario']]);

        // Borrar token ya usado
        $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = ?");
        $stmt->execute([$token]);

        $mensaje      = '¡Contraseña actualizada! Ya puedes iniciar sesión con tu nueva contraseña.';
        $tipo         = 'ok';
        $token_valido = false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Nueva contraseña</title>
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
        .password-hint {
            font-size: 0.78rem;
            color: #999;
            text-align: left;
            margin-top: -10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>FinishLine</h1>
        <p class="welcome-text">Introduce tu nueva contraseña.</p>

        <?php if ($mensaje && $tipo === 'error'): ?>
            <div class="error-mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php elseif ($mensaje && $tipo === 'ok'): ?>
            <div class="ok-mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($token_valido): ?>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="password" name="password"  placeholder="Nueva contraseña"     required>
            <p class="password-hint">Mínimo 6 caracteres</p>
            <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
            <button type="submit">Guardar contraseña</button>
        </form>
        <?php endif; ?>

        <p class="back-link">
            <?php if ($tipo === 'ok'): ?>
                <a href="/src/views/login.php">Ir al inicio de sesión →</a>
            <?php else: ?>
                <a href="/src/views/recuperar_password.php">← Solicitar nuevo enlace</a>
            <?php endif; ?>
        </p>
    </div>
</body>
</html>
