<?php 
$usuarioLogueado = isset($_SESSION['usuario']);
$pagina_actual = basename($_SERVER['PHP_SELF']); //ruta actual del usuario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Pintura Industrial</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Header.css">
</head>
<body>
    <header class="header-top">
        <div class="header-contact">Servicios Especializados de Pintura Industrial</div>
        <div class="auth-section">
            <?php if ($usuarioLogueado): ?>
                <span>Bienvenido, <strong><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></strong></span>
                <a href="/src/views/logout.php" class="btn-logout">Cerrar Sesion</a>
            <?php else: ?>
                <a href="/src/views/login.php" class="btn-login">Iniciar Sesion</a>
            <?php endif; ?>
        </div>
    </header>

    <nav class="main-nav">
        <div class="logo">FINISH<span>LINE</span></div>
        <div class="menu">

            <!--Navegacion-->
            <a href="/index.php" class="<?= ($pagina_actual == 'index.php') ? 'active' : '' ?>">Inicio</a>
            <a href="/src/views/quienes_somos.php" class="<?= ($pagina_actual == 'quienes_somos.php') ? 'active' : '' ?>">Quiénes somos</a>
            
            <a href="/src/views/galeria.php" class="<?= ($pagina_actual == 'galeria.php') ? 'active' : '' ?>">Galería</a>
            <?php if($usuarioLogueado): ?>
            <a href="/src/views/servicios.php" class="<?= ($pagina_actual == 'servicios.php') ? 'active' : '' ?>">Servicios</a>
            <a href="/src/views/presupuesto.php" class="<?= ($pagina_actual == 'presupuesto.php') ? 'active' : '' ?>">Presupuesto</a>
            <!--Cambia el contacto a personas logeadas-->
            <?php else: ?>
            <a href="/src/views/contacto.php" class="<?= ($pagina_actual == 'contacto.php') ? 'active' : '' ?>">Contacto</a>
            <?php endif; ?>
            <!--Aparece servicios solo a personas logeadas-->
            <?php if (isset($_SESSION['usuario']) && in_array($_SESSION['usuario']['rol'], ['admin','empleado'])): ?>
                <a href="/src/views/admin/panel.php" style="background:#718096;color:#fff;padding:6px 14px;border-radius:6px;font-size:13px;font-weight:700;text-decoration:none;">&#9881; Panel de control</a>
            <?php endif; ?>

        </div>
    </nav>