<?php 
session_start(); 
require_once __DIR__ . '/src/config/conexion.php';
require_once __DIR__ . '/src/config/roles.php';


require_once __DIR__ . '/src/includes/header.php';
$usuarioLogueado = isset($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Pintura Industrial</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Index.css">
    <link rel="stylesheet" href="/src/styles/Footer.css">
</head>
<body>

    <section class="welcome-banner">
        <div class="banner-content">
            <h1>Bienvenido a FINISHLINE</h1>
            <p>Especialistas en chapa, pintura y acabados profesionales de alta resistencia para el sector industrial.</p>
            <a href="#servicios" class="btn-primary">Descubrir más</a>
        </div>
    </section>

    <section class="section-info">
        <h2>Líderes en el sector</h2>
        <div class="divider"></div>
        <p>Ofrecemos soluciones integrales para la industria, garantizando durabilidad y estética en cada proyecto.</p>
    </section>

    <section id="servicios" class="services-wrapper">
        <div class="services-grid">
            <div class="service-card">
                <div class="service-image"><img src="img/imagen1.jpg" alt="Coche Gris"></div>
                <div class="service-content">
                    <h3>Su empresa de confianza</h3>
                    <p>Pintura industrial de la más alta calidad.</p>
                </div>
            </div>
            
            <div class="service-card">
                <div class="service-image"><img src="img/imagen2.jpg" alt="Coche Rojo"></div>
                <div class="service-content">
                    <h3>Procesos homologados</h3>
                    <p>Calidad certificada y garantizada en cada acabado.</p>
                </div>
            </div>
            
            <div class="service-card">
                <div class="service-image"><img src="img/imagen3.jpg" alt="Cabina"></div>
                <div class="service-content">
                    <h3>Asesoría técnica</h3>
                    <p>Expertos a su entera disposición en todo momento.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="services-wrapper">
        <div class="services-grid">
            <div class="service-card">
                <div class="service-image"><img src="img/Finalizado.jpeg" alt="Coche Gris"></div>
                <div class="service-content">
                    <h3>Rapidez y eficiencia</h3>
                    <p>Optimización de tiempos de entrega sin comprometer el resultado.</p>
                </div>
            </div>
            
            <div class="service-card">
                <div class="service-image"><img src="img/PiezaPreparada.png" alt="Coche Rojo"></div>
                <div class="service-content">
                    <h3>Preparación</h3>
                    <p>Cuidado meticuloso en la superficie para un anclaje y brillo perfectos.</p>
                </div>
            </div>
            
           
        </div>
    </section>
    <?php if (!isset($_SESSION['usuario'])): ?>
    <section class="premium-notice">
        <div class="premium-card">
            <h3>Nuestros Servicios Premium</h3>
            <p>El catálogo detallado de procesos y acabados está disponible exclusivamente para clientes registrados.</p>
            <a href="/src/views/login.php" class="btn-link">Inicia sesión</a>
        </div>
    </section>
<?php endif; ?>
    <?php require_once __DIR__ . '/src/includes/carrusel-comentarios.php';
          require_once __DIR__ . '/src/includes/footer.php';
     ?>

</body>
</html>