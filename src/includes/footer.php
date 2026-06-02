<?php 
$usuarioLogueado = isset($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Pintura Industrial</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Footer.css">
</head>

<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-col">
            <div class="logo-footer">
                FINISH<span>LINE</span>
            </div>
            <p class="footer-description">
                Expertos en recubrimientos industriales y acabados de alta resistencia. 
                Calidad certificada para proyectos de máxima exigencia.
            </p>
            <div class="social-links">  
                <a href="https://www.linkedin.com/" aria-label="LinkedIn"><span>in</span></a>
                <a href="https://www.instagram.com/" aria-label="Instagram"><span>ig</span></a>
                <a href="https://www.facebook.com/" aria-label="Facebook"><span>fb</span></a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Navegación</h4>
            <ul style="padding-left: 0;">
                <li><a href="/index.php">Inicio</a></li>
                <li><a href="/src/views/quienes_somos.php">Quiénes Somos</a></li>
                <li><a href="/src//views/galeria.php">Galería de Proyectos</a></li>
                <?php if (!isset($_SESSION['usuario'])): ?>
                    <li><a href="/src/views/contacto.php">Contacto</a></li>
                <?php else: ?>
                    <li><a href="/src/views/presupuesto.php">Presupuesto</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Servicios</h4>
            <ul style="padding-left: 0;">
                <!--muestra solo LOGEADOS-->
                <?php if ($usuarioLogueado): ?>
                    <li><a href="/src/views/servicios.php#lijado-completo">Lijado Superficial + Pintado Entero</a></li>
                    <li><a href="/src/views/servicios.php#lijado-piezas">Lijado + Pintado por Piezas</a></li>
                    <li><a href="/src/views/servicios.php#chapa-pintura">Chapa + Pintura</a></li>
                    <li><a href="/src/views/servicios.php#pulido">Pulido Profesional</a></li>
                <?php else: ?>
                    <li><p class="footer-restricted">Solo permitido a clientes. <a href="/src/views/login.php">Inicia sesión</a> para ver nuestros servicios.</p></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contacto Directo</h4>
            <p class="contact-text">📍 Villaverde Bajo, Calle del Diamante, 40, Madrid, España</p>
            <p class="contact-text">📞 +34 912 345 678</p>
            <p class="contact-text">✉️ finishlineheesni@gmail.com</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> FinishLine. Todos los derechos reservados.</p>
        <div class="footer-legal">
            <a href="#">Aviso Legal</a> | <a href="#">Privacidad</a>
        </div>
    </div>
</footer>