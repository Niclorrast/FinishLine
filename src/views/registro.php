<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/conexion.php'; 
require_once __DIR__ . '/../controllers/AuthController.php';

$database = new Database();
$conn     = $database->getConnection();

if (!isset($conn)) {
    die("Error: La conexión a la base de datos no está disponible.");
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController($conn); 
    $resultado  = $controller->registro($_POST);
    
    if (isset($resultado['success'])) {
        header('Location: /index.php?registrado=1'); 
        exit();
    } else {
        $mensaje = $resultado['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - FinishLine</title>
    <link rel="stylesheet" href="/src/styles/login.css">
    <style>
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
        <p class="welcome-text">
            Crea tu cuenta para acceder a todos nuestros servicios de chapa y pintura.
        </p>

        <?php if ($mensaje): ?>
            <div class="error-mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text"     name="nombre"   placeholder="Nombre"             required>
            <input type="text"     name="apellido" placeholder="Apellido"            required>
            <input type="text"     name="telefono" placeholder="Teléfono (opcional)">
            <input type="email"    name="correo"   placeholder="Correo electrónico"  required>
            <input type="password" name="password" placeholder="Contraseña"          required>
            <p class="password-hint">Mínimo 6 caracteres</p>
            <button type="submit">Crear cuenta</button>
        </form>

        <p class="footer-link">
            ¿Ya tienes cuenta? <a href="/src/views/login.php">Inicia sesión aquí</a>
        </p>
        <p class="footer-link">
            <a href="/index.php">← Volver a la página principal</a>
        </p>
    </div>
</body>
</html>