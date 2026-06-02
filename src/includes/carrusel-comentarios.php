<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/styles/Carrusel-comtarios.css">
    <title>Document</title>
</head>
<body>
    <!-- Comentarios-->
<section class="reviews-section">
    <div class="container text-center">
        <h2 class="mb-5">Lo que dicen nuestros clientes</h2>

        <!--HACER TODOS LOS SIMULADOS-->
        <div id="carruselComentarios">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="review-card">
                        <p class="review-text">"Trabajo impecable, el acabado quedó perfecto. Totalmente recomendable."</p>
                        <div class="review-stars">★★★★★</div>
                        <span class="review-author">— Carlos M., cliente industrial</span>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="review-card">
                        <p class="review-text">"Profesionales de verdad. Cumplieron plazos y el resultado superó nuestras expectativas."</p>
                        <div class="review-stars">★★★★★</div>
                        <span class="review-author">— Laura P., taller mecánico</span>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="review-card">
                        <p class="review-text">"La mejor empresa de pintura industrial. Calidad garantizada."</p>
                        <div class="review-stars">★★★★☆</div>
                        <span class="review-author">— Antonio R., flota de vehículos</span>
                    </div>
                </div>
            </div>

            <div class="custom-indicators">
                <button class="dot active" onclick="irASlide(0)"></button>
                <button class="dot" onclick="irASlide(1)"></button>
                <button class="dot" onclick="irASlide(2)"></button>
            </div>

            <!--Botones de movimiento-->
            <!--Suma 1 o resta 1-->
            <button class="btn btn-primary mt-4" onclick="moverCarrusel(-1)">Anterior</button>
            <button class="btn btn-primary mt-4" onclick="moverCarrusel(1)">Siguiente</button>
        </div>
    </div>
   
</section>
 <script src="/src/scripts/moverCarrusel.js"></script>
</body>
</html>
