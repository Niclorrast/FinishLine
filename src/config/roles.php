<?php
//Metodo para verificar si tiene rol + redireccionar a inicio de sesion
function requireLogin(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: /src/views/login.php');
        exit();
    }
}

//requireLogin + sino es admin redirige a inicio
function requireAdmin(): void {
    requireLogin();
    if ($_SESSION['usuario']['rol'] !== 'admin') {
        header('Location: /index.php');
        exit();
    }
}

//requireLogin() pero si no es empleado o admin redirige a inicio
function requireAdminOrEmpleado(): void {
    requireLogin();
    $rol = $_SESSION['usuario']['rol'];
    if ($rol !== 'admin' && $rol !== 'empleado') {
        header('Location: /index.php');
        exit();
    }
}

//devuelve rol de la sesion
function esAdmin(): bool {
    return isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'admin';
}

//devuelve rol de la sesion
function esEmpleado(): bool {
    return isset($_SESSION['usuario']['rol']) && $_SESSION['usuario']['rol'] === 'empleado';
}

//devuelve rol de la sesion
function rolActual(): string {
    return $_SESSION['usuario']['rol'] ?? 'cliente';
}
