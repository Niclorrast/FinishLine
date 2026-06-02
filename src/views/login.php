<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../controllers/AuthController.php';

// Conexión usando la clase Database con variables de entorno
$database = new Database();
$conn     = $database->getConnection();

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo']   ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($correo) || empty($password)) {
        $mensaje = 'Por favor, completa todos los campos.';
    } else {
        $controller = new AuthController($conn);
        $resultado  = $controller->login($correo, $password);

        if ($resultado === true) {
            $rol = $_SESSION['usuario']['rol'] ?? 'cliente';
            if (in_array($rol, ['admin', 'empleado'])) {
                header('Location: /src/views/admin/panel.php');
            } else {
                header('Location: /index.php');
            }
            exit();
        } else {
            $mensaje = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-FinishLine</title>
    
    <link rel="stylesheet" href="/src/styles/login.css">
</head>
<body>
    <div class="login-container">
        <h1>FinishLine</h1>
        <p class="welcome-text">
            Bienvenido a FinishLine, para conocer más de nosotros y nuestro servicio
            por favor regístrate e inicia sesión.
        </p>

        <?php if ($mensaje): ?>
            <div class="error-mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email"    name="correo"   placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña"         required>
            <button type="submit">Entrar</button>
        </form>

        <p class="footer-link recuperar-link">
            <a href="/src/views/recuperar_password.php">¿Olvidaste tu contraseña?</a>
        </p>
        <p class="footer-link">
            ¿No tienes cuenta? <a href="/src/views/registro.php">Regístrate aquí</a>
        </p>
        <p class="footer-link">
            <a href="/index.php">← Volver a la página principal</a>
        </p>
    </div>
</body>
</html>