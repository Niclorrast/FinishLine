<?php 
session_start();
require_once __DIR__ . '/../config/conexion.php'; 
// El header se incluye abajo dentro del body para mantener la estructura HTML correcta
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinishLine - Quiénes Somos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Header.css">
    <link rel="stylesheet" href="/src/styles/Footer.css">
    <link rel="stylesheet" href="/src/styles/QuienesSomos.css">
</head>
<body>

    <?php require_once __DIR__ . '/../includes/header.php'; ?>

    <!-- BANNER -->
    <section class="qs-banner">
        <div class="qs-banner-content">
            <h1>Quiénes Somos</h1>
            <p>Pasión por el detalle, compromiso con la calidad</p>
        </div>
    </section>

    <!-- NUESTRA HISTORIA -->
    <section class="qs-historia">
        <div class="qs-container">
            <div class="qs-texto">
                <h2>Nuestra Historia</h2>
                <div class="divider"></div>
                <p>FinishLine nació con una vocación clara: ofrecer los más altos estándares en chapa, pintura y acabados industriales. Con más de 10 años de experiencia en el sector, hemos trabajado con flotas de vehículos, talleres mecánicos y empresas industriales de toda España.</p>
                <p>Desde nuestras instalaciones en Madrid, combinamos tecnología de vanguardia con el conocimiento técnico de nuestro equipo para garantizar resultados excepcionales en cada proyecto.</p>
            </div>

            <div class="qs-imagen">
                <!-- CARRUSEL DE IMÁGENES -->
                <div id="carouselHistoria" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    
                    <!-- Indicadores -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselHistoria" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselHistoria" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselHistoria" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#carouselHistoria" data-bs-slide-to="3" aria-label="Slide 4"></button>
                    </div>

                    <!-- Contenedor de las imágenes con la clase custom-carousel-img para CSS -->
                    <div class="carousel-inner">
                        <!-- 1. CABINA DE PINTURA -->
                        <div class="carousel-item active">
                            <img src="/img/Cabina_Pintura.jpg" class="d-block w-100 custom-carousel-img" alt="Cabina de Pintura">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Nuestras Instalaciones</h5>
                                <p>Cabina de pintura de última tecnología.</p>
                            </div>
                        </div>

                        <!-- 2. PERSONA PINTANDO -->
                        <div class="carousel-item">
                            <img src="/img/Pintor.jpeg" class="d-block w-100 custom-carousel-img" alt="Operario Pintando">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Mano de Obra Cualificada</h5>
                                <p>Técnicos especialistas en aplicación.</p>
                            </div>
                        </div>

                        <!-- 3. PROCESO -->
                        <div class="carousel-item">
                            <img src="/img/Preparacion.jpg" class="d-block w-100 custom-carousel-img" alt="Proceso de Preparación">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>El Proceso de Preparación</h5>
                                <p>La base para un acabado perfecto.</p>
                            </div>
                        </div>

                        <!-- 4. ACABADO -->
                        <div class="carousel-item">
                            <img src="/img/FInal.jpeg" class="d-block w-100 custom-carousel-img" alt="Resultado Final">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Acabado Final</h5>
                                <p>Brillo y durabilidad garantizados.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHistoria" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselHistoria" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div> <!-- Cierre correcto del carrusel -->
            </div>
        </div>
    </section>

    <!-- MISIÓN Y VISIÓN -->
    <section class="qs-mision">
        <div class="qs-container qs-mision-grid">
            <div class="qs-mision-card">
                <span class="qs-icon">🎯</span>
                <h3>Nuestra Misión</h3>
                <p>Devolver cada vehículo o pieza a su estado óptimo, combinando técnica, precisión y los mejores materiales del mercado.</p>
            </div>
            <div class="qs-mision-card">
                <span class="qs-icon">🔭</span>
                <h3>Nuestra Visión</h3>
                <p>Ser la empresa de referencia en acabados industriales en España, reconocida por su innovación y calidad.</p>
            </div>
            <div class="qs-mision-card">
                <span class="qs-icon">💎</span>
                <h3>Nuestros Valores</h3>
                <p>Honestidad, precisión técnica y orientación al cliente son los pilares que guían cada trabajo.</p>
            </div>
        </div>
    </section>

    <!-- POR QUÉ ELEGIRNOS -->
    <section class="qs-elegir">
        <div class="qs-container">
            <h2 class="text-center">¿Por qué elegirnos?</h2>
            <div class="divider mx-auto"></div>
            <div class="qs-elegir-grid">
                <div class="qs-elegir-card">
                    <span class="qs-icon">🔧</span>
                    <h4>Experiencia</h4>
                    <p>Más de 10 años en el sector industrial avalando cada trabajo.</p>
                </div>
                <div class="qs-elegir-card">
                    <span class="qs-icon">✅</span>
                    <h4>Calidad Certificada</h4>
                    <p>Procesos homologados que garantizan resultados de primer nivel.</p>
                </div>
                <div class="qs-elegir-card">
                    <span class="qs-icon">⚡</span>
                    <h4>Rapidez</h4>
                    <p>Cumplimos plazos sin sacrificar la calidad final.</p>
                </div>
                <div class="qs-elegir-card">
                    <span class="qs-icon">🛡️</span>
                    <h4>Garantía</h4>
                    <p>Trabajos con garantía de acabado y satisfacción total.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- NUESTRO EQUIPO -->
    <section class="qs-equipo">
        <div class="qs-container">
            <h2 class="text-center">Nuestro Equipo</h2>
            <div class="divider mx-auto"></div>
            <div class="qs-equipo-grid">
                <div class="qs-equipo-card">
                    <div class="qs-avatar">
                        <img src="/img/Nico.jpeg" alt="Nicolas Arribas">
                    </div>
                    <h4>Nicolás Arribas Jiménez</h4>
                    <span>Director Técnico</span>
                    <p>Líder experto en proyectos de pintura industrial.</p>
                </div>
                <div class="qs-equipo-card">
                    <div class="qs-avatar">
                        <img src="/img/Jhorib.png" alt="Jhorib Stephano">
                    </div>
                    <h4>Jhorib Stephano Sequeiros</h4>
                    <span>Especialista en Chapa</span>
                    <p>Experto en reparación y modelado de carrocerías.</p>
                </div>
                <div class="qs-equipo-card">
                    <div class="qs-avatar">
                        <img src="/img/HenryCantoral.jpeg" alt="Henry Cantoral">
                    </div>
                    <h4>Henry Cantoral Florian</h4>
                    <span>Técnico de Pintura</span>
                    <p>Especializado en acabados de alta resistencia.</p>
                </div>
            </div>
        </div>
    </section>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>