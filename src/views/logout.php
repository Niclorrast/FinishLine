<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$database   = new Database();
$conn       = $database->getConnection();

$controller = new AuthController($conn);
$controller->logout();
?>