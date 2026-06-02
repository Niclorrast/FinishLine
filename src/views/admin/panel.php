<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../models/adminmodel.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

/* ── 1. Autenticación ── */
if (empty($_SESSION['usuario'])) {
    header('Location: /src/views/auth/login.php'); exit;
}


$rolActual  = $_SESSION['usuario']['rol'] ?? 'cliente';
$idActual   = (int)($_SESSION['usuario']['id'] ?? 0);
$esAdmin    = ($rolActual === 'admin');
$esEmpleado = ($rolActual === 'empleado');

if (!$esAdmin && !$esEmpleado) {
    header('Location: /index.php'); exit;
}

$database = new Database();
$conn     = $database->getConnection();
$admin    = new AdminController($conn);

/* ── 2. Procesar POST ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    $accionesAdmin    = ['cambiar_rol','eliminar_usuario','crear_empleado','cambiar_estado_ticket','responder_ticket','responder_solicitud'];
    $accionesComunes  = ['guardar_nota'];

    if ($esAdmin && in_array($accion, $accionesAdmin)) {
        $admin->handle();
    }

    if (($esAdmin || $esEmpleado) && in_array($accion, $accionesComunes)) {
        $admin->handle();
    }

    if ($esEmpleado && $accion === 'cambiar_estado_ticket') {
        $admin->handle();
    }

    header('Location: ' . $_SERVER['REQUEST_URI']); exit;
}

/* ── 3. Leer flash ── */
$mensajeEmpleado     = $_SESSION['flash_empleado']['msg']  ?? '';
$tipoMensajeEmpleado = $_SESSION['flash_empleado']['tipo'] ?? '';
unset($_SESSION['flash_empleado']);

/* ── 4. Colores ── */
$colores_estado = [
        'pendiente'  => '#f59e0b',
        'en proceso' => '#3b82f6',
        'completado' => '#10b981',
        'cancelado'  => '#ef4444',
];
$colores_rol = [
        'admin'    => '#7c3aed',
        'empleado' => '#0891b2',
        'cliente'  => '#64748b',
];

/* ── 5. Cargar datos según rol ── */
$notasPorTicket         = [];
$respuestasPorTicket    = [];
$respuestasPorSolicitud = [];
$documentosPorTicket    = [];

if ($esAdmin) {
    $stats       = $admin->model->getStats();
    $tickets     = $admin->model->getTodosTickets();
    $usuarios    = $admin->model->getUsuarios();
    $solicitudes = $admin->model->getSolicitudes();
    $empleados   = $admin->model->getEmpleados();

    foreach ($tickets as $t) {
        $id = $t['id_ticket'];
        $notasPorTicket[$id]       = $admin->model->getNotasTicket($id);
        $respuestasPorTicket[$id]  = $admin->model->getRespuestasTicket($id);
        $documentosPorTicket[$id]  = $admin->model->getDocumentosTicket($id);
    }

    foreach ($solicitudes as $s) {
        $id = $s['id_solicitud'];
        $respuestasPorSolicitud[$id] = $admin->model->getRespuestasSolicitud($id);
    }

} else {
    $stats   = $admin->model->getStatsEmpleado($idActual);
    $tickets = $admin->model->getTicketsEmpleado($idActual);

    foreach ($tickets as $t) {
        $id = $t['id_ticket'];
        $notasPorTicket[$id]      = $admin->model->getNotasTicket($id);
        $respuestasPorTicket[$id] = $admin->model->getRespuestasTicket($id);
        $documentosPorTicket[$id] = $admin->model->getDocumentosTicket($id);
    }
}

/* ── 6. Helper ── */
function esAdmin(): bool {
    return ($_SESSION['usuario']['rol'] ?? '') === 'admin';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel – FinishLine</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Admin.css">
    <link rel="stylesheet" href="/src/styles/darkmode.css">
    <link rel="icon" type="image/x-icon" href="/src/assets/favicon.ico">
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">FINISH<span>LINE</span></div>
    <div class="sidebar-rol">Panel de <?= $esAdmin ? 'Administración' : 'Empleado' ?></div>
    <nav class="sidebar-nav">
        <?php if ($esAdmin): ?>
            <a href="#" class="sidebar-link activo" onclick="mostrarTab(event,'dashboard')">Dashboard</a>
            <a href="#" class="sidebar-link"        onclick="mostrarTab(event,'usuarios')">Usuarios</a>
            <a href="#" class="sidebar-link"        onclick="mostrarTab(event,'tickets')">Tickets</a>
            <a href="#" class="sidebar-link"        onclick="mostrarTab(event,'solicitud')">Solicitudes</a>
            <a href="#" class="sidebar-link"        onclick="mostrarTab(event,'empleados')">Empleados</a>
        <?php else: ?>
            <a href="#" class="sidebar-link activo">Mis Tickets</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <span>👤 <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') ?></span>
        <a href="/src/views/logout.php" class="btn-logout-admin">Cerrar sesión</a>
        <a href="/index.php"            class="btn-volver-admin">← Volver al sitio</a>
        <button id="toggleDark" class="btn-darkmode">
            🌙 Modo nocturno
        </button>
    </div>
</aside>

<main class="admin-main">
    <?php if ($esAdmin): ?>
        <?php include __DIR__ . '/dashboard.php'; ?>
        <?php include __DIR__ . '/tickets.php';   ?>
        <?php include __DIR__ . '/usuarios.php';  ?>
        <?php include __DIR__ . '/solicitud.php'; ?>
        <?php include __DIR__ . '/empleados.php'; ?>
        <?php include __DIR__ . '/modals.php';    ?>
    <?php else: ?>
        <?php include __DIR__ . '/empleado.php';  ?>
    <?php endif; ?>
</main>

<script>
    function mostrarTab(e, tab) {
        e.preventDefault();
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('activo'));
        document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('activo'));
        document.getElementById('tab-' + tab)?.classList.add('activo');
        e.target.classList.add('activo');
    }
</script>
<script src="/src/scripts/darkMode.js"></script>
</body>
</html>