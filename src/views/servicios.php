<?php 
session_start();
require_once __DIR__ . '/../config/conexion.php'; 
require_once __DIR__ . '/../includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Servicios</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Servicios.css">
    <link rel="stylesheet" href="/src/styles/Footer.css">
</head>

<main class="servicios-container">

    <!-- ENCABEZADO -->
    <div class="servicios-header">
        <h1>Nuestros <span>Servicios</span></h1>
        <div class="divider"></div>
        <p>Tratamientos profesionales de chapa y pintura con los más altos estándares de calidad industrial.</p>
    </div>

<div class="servicio-card" id="lijado-entero">
    <div class="servicio-imagen carrusel-wrapper">
        <div class="carrusel-slide activo">
            <img src="/img/Lijado.jpg" alt="Paso 1: Lijado">
            <div class="carrusel-etiqueta">LIJADO</div>
        </div>
        <div class="carrusel-slide">
            <img src="/img/Proceso.jpg" alt="Paso 2: Imprimación">
            <div class="carrusel-etiqueta">PROCESO</div>
        </div>
        <div class="carrusel-slide">
            <img src="/img/Finalizado.jpeg" alt="Paso 3: Resultado final">
            <div class="carrusel-etiqueta despues">FINALIZADO</div>
        </div>
        <button class="carrusel-btn prev" onclick="moverCarrusel(this, -1)">&#8249;</button>
        <button class="carrusel-btn next" onclick="moverCarrusel(this, 1)">&#8250;</button>
        <div class="carrusel-dots">
            <span class="dot activo"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
        <div class="servicio-badge">Servicio Completo</div>
    </div>
        <div class="servicio-info">
            <div class="servicio-numero">01</div>
            <h2>Lijado Superficial <span>+</span> Pintado Entero</h2>
            <div class="servicio-divider"></div>
            <p class="servicio-descripcion">
                Devuelve a tu vehículo el aspecto y el valor del primer día. Nuestro proceso de preparación profunda elimina el desgaste acumulado por los años, el sol y los pequeños roces. Aplicamos tecnología de pintura de última generación para garantizar un brillo espejo y una protección duradera que transformará por completo la estética de tu coche.
            </p>
            <ul class="servicio-lista">
                <li>✔ Tratamiento de superficie contra porosidad y micro-oxidación.</li>
                <li>✔ Sellado de carrocería con imprimación termofusionada.</li>
                <li>✔ Pintado bicapa de alta resistencia industrial.</li>
                <li>✔ Barnizado premium con protección UV extra.</li>
            </ul>
            <a href="/src/views/presupuesto.php" class="btn-precio">
                <span>Consultar Presupuesto</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>

    

    <!-- SERVICIO 2: CHAPA + PINTURA -->
    <div class="servicio-card" id="chapa-pintura">
        <div class="servicio-imagen carrusel-wrapper">
            <!-- SLIDE ANTES -->
            <div class="carrusel-slide activo">
                <img src="/img/imagen8.jpeg" alt="Antes - daños en la carrocería">
                <div class="carrusel-etiqueta">ANTES</div>
            </div>
            <!-- SLIDE DESPUÉS -->
            <div class="carrusel-slide">
                <img src="/img/imagen9.jpeg" alt="Después - chapa y pintura terminada">
                <div class="carrusel-etiqueta despues">DESPUÉS</div>
            </div>
            <!-- CONTROLES -->
            <button class="carrusel-btn prev" onclick="moverCarrusel(this, -1)" aria-label="Anterior">&#8249;</button>
            <button class="carrusel-btn next" onclick="moverCarrusel(this, 1)" aria-label="Siguiente">&#8250;</button>
            <!-- PUNTOS -->
            <div class="carrusel-dots">
                <span class="dot activo"></span>
                <span class="dot"></span>
            </div>
            <div class="servicio-badge">Más Solicitado</div>
        </div>
        <div class="servicio-info">
            <div class="servicio-numero">02</div>
            <h2>Chapa <span>+</span> Pintura</h2>
            <div class="servicio-divider"></div>
            <p class="servicio-descripcion">
                Servicio integral de reparación estructural y estética para vehículos con daños por golpes, accidentes o deformaciones en la carrocería. Nuestros especialistas en chapa reconstruyen y alinean las piezas afectadas con herramientas de precisión antes de proceder al pintado definitivo. Un servicio completo que devuelve al vehículo su forma y aspecto originales, por muy serio que sea el daño.
            </p>
            <ul class="servicio-lista">
                <li>✔ Reparación de abolladuras y deformaciones</li>
                <li>✔ Reconstrucción de zonas con daño estructural</li>
                <li>✔ Masilla, lijado fino y pintura de acabado</li>
                <li>✔ Resultado sin rastro del daño original</li>
            </ul>
            <a href="/src/views/presupuesto.php" class="btn-precio">
                <span>Consultar Presupuesto</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>

    <!-- SERVICIO 3: PULIDO DE COCHE -->
    <div class="servicio-card reverse" id="pulido">
        <div class="servicio-imagen carrusel-wrapper">
    <div class="carrusel-slide activo">
        <img src="/img/imagen11.jpeg" alt="Antes del pulido">
    </div>
    <div class="carrusel-slide">
        <img src="/img/Pulido2.jpeg" alt="Resultado del pulido">
    </div>
    <div class="carrusel-slide">
        <img src="/img/Pulido3.jpeg" alt="Resultado del pulido">
    </div>
    <button class="carrusel-btn prev" onclick="moverCarrusel(this, -1)">&#8249;</button>
    <button class="carrusel-btn next" onclick="moverCarrusel(this, 1)">&#8250;</button>
    <div class="carrusel-dots">
    <span class="dot activo"></span>
    <span class="dot"></span>
    <span class="dot"></span>  <!-- ← Este faltaba -->
</div>
    <div class="servicio-badge">Acabado Premium</div>
</div>
        <div class="servicio-info">
            <div class="servicio-numero">03</div>
            <h2>Pulido <span>Profesional</span></h2>
            <div class="servicio-divider"></div>
            <p class="servicio-descripcion">
                Tratamiento estético de alto nivel para recuperar el brillo y la profundidad de color de la pintura original. Con maquinaria de pulido orbital y productos abrasivos de precisión, eliminamos micro-arañazos, marcas de lavado, oxidación superficial y pérdida de brillo acumulada con los años. El resultado es una pintura rejuvenecida, con aspecto de nueva, que protege la carrocería y aumenta el valor del vehículo.
            </p>
            <ul class="servicio-lista">
                <li>✔ Eliminación de micro-arañazos y marcas</li>
                <li>✔ Recuperación del brillo original de la pintura</li>
                <li>✔ Pulido con maquinaria orbital profesional</li>
                <li>✔ Acabado con cera o sellado cerámico opcional</li>
            </ul>
            <a href="/src/views/presupuesto.php" class="btn-precio">
                <span>Consultar Presupuesto</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
        
    </div>
    <!-- SERVICIO 4: LIJADO + PINTADO POR PIEZAS -->
    <div class="servicio-card reverse" id="lijado-piezas">
        <div class="servicio-imagen carrusel-wrapper">
    <div class="carrusel-slide activo">
        <img src="/img/PiezaPreparada.png" alt="Reparación por piezas">
        <div class="carrusel-etiqueta">ANTES</div>
    </div>
    <div class="carrusel-slide">
        <img src="/img/PiezaTerminada.png" alt="Pieza reparada">
        <div class="carrusel-etiqueta despues">DESPUÉS</div>
    </div>
    <button class="carrusel-btn prev" onclick="moverCarrusel(this, -1)">&#8249;</button>
    <button class="carrusel-btn next" onclick="moverCarrusel(this, 1)">&#8250;</button>
    <div class="carrusel-dots">
        <span class="dot activo"></span>
        <span class="dot"></span>
    </div>
    <div class="servicio-badge">Por Piezas</div>
</div>
        <div class="servicio-info">
            <div class="servicio-numero">04</div>
            <h2>Micro-Reparación <span>+</span> Pintado Selectivo</h2>
            <div class="servicio-divider"></div>
            <p class="servicio-descripcion">
            ¿Un roce en el parachoques o un arañazo en la puerta arruina la estética de tu coche? No es necesario un repintado general para recuperar la perfección. Nos especializamos en la técnica de difuminado y ajuste cromático de alta precisión            </p>
            <ul class="servicio-lista">
                <li>✔ Tecnología Color-Match: Igualación digital del tono exacto.</li>
                <li>✔ Difuminado Invisible: Acabado sin cortes con la pintura original.</li>
                <li>✔ Reparación Focalizada: Ideal para parachoques y puertas.</li>
                <li>✔ Entrega Express: Reparación rápida al trabajar por paneles.</li>
            </ul>
            <a href="/src/views/presupuesto.php" class="btn-precio">
                <span>Consultar Presupuesto</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
        

        
    </div>
    <!-- SERVICIO 5: RESTAURACIÓN DE LLANTAS -->
<div class="servicio-card" id="restauracion-llantas">
    <div class="servicio-imagen carrusel-wrapper">
        <div class="carrusel-slide activo">
            <img src="/img/Llanta.png" alt="Llanta1">
        </div>
        <div class="carrusel-slide">
            <img src="/img/Llanta3.png" alt="Llanta2">
        </div>
        <button class="carrusel-btn prev" onclick="moverCarrusel(this, -1)">&#8249;</button>
        <button class="carrusel-btn next" onclick="moverCarrusel(this, 1)">&#8250;</button>
        <div class="carrusel-dots">
            <span class="dot activo"></span>
            <span class="dot"></span>
        </div>
        <div class="servicio-badge">Llantas como nuevas</div>
    </div>
    <div class="servicio-info">
        <div class="servicio-numero">05</div>
        <h2>Restauración de <span>Llantas</span></h2>
        <div class="servicio-divider"></div>
        <p class="servicio-descripcion">
            Las llantas son el elemento más expuesto al rozamiento con bordillos, baches y el desgaste diario. 
            Nuestro servicio de restauración devuelve a cada llanta su geometría original, elimina arañazos, 
            oxidación y golpes superficiales, y aplica un acabado protector que las deja con el aspecto de recién 
            salidas de fábrica. Trabajamos con llantas de aluminio, aleación y acero en cualquier tamaño.
        </p>
        <ul class="servicio-lista">
            <li>✔ Reparación de rozaduras, arañazos y golpes en el aro.</li>
            <li>✔ Tratamiento anticorrosión de base para mayor durabilidad.</li>
            <li>✔ Pintura en color original o personalizado a tu elección.</li>
            <li>✔ Barniz protector de acabado brillante o mate.</li>
        </ul>
        <a href="/src/views/presupuesto.php" class="btn-precio">
            <span>Consultar Presupuesto</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>
</div>

    <script src="/src/scripts/moverCarrusel.js"></script> <!-- Mover foto chapa y pintura  -->

    <div class="servicios-cta">
        <h3>¿No encuentras lo que buscas?</h3>
        <p>Contacta con nuestro equipo y te asesoramos sin compromiso sobre cualquier trabajo de chapa y pintura.</p>
        <a href="/src/views/presupuesto.php" class="btn-cta-final">Hablar con un especialista</a>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</html>