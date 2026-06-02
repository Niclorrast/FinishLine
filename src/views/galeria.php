<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../includes/header.php';

// =============================================
//  CONFIGURACIÓN — cambia solo esta ruta
// =============================================
$carpeta_fotos = __DIR__ . '/../../img/Galeria/';
$carpeta_web   = '/img/Galeria/';

$extensiones = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

$fotos = [];
if (is_dir($carpeta_fotos)) {
    foreach (new DirectoryIterator($carpeta_fotos) as $archivo) {
        if ($archivo->isFile()) {
            $ext = strtolower($archivo->getExtension());
            if (in_array($ext, $extensiones)) {
                $fotos[] = [
                        'web'   => $carpeta_web . $archivo->getFilename(),
                        'mtime' => $archivo->getMTime(),
                ];
            }
        }
    }
    usort($fotos, fn($a, $b) => $b['mtime'] - $a['mtime']);
}

$total = count($fotos);
?>

    <link rel="stylesheet" href="/src/styles/Galeria.css">

    <main class="galeria-main">

        <!-- HERO -->
        <section class="galeria-hero">
            <div class="galeria-hero-content">
                <h1>Galería de <span>Proyectos</span></h1>
                <p>Cada vehículo es una historia de transformación. Aquí mostramos los resultados que avalan nuestra experiencia.</p>
                <?php if ($total > 0): ?>
                    <p class="galeria-hero-count"><strong><?= $total ?></strong> fotos publicadas</p>
                <?php endif; ?>
            </div>
            <div class="galeria-hero-deco"></div>
        </section>

        <!-- GRID UNIFORME -->
        <section class="galeria-grid-section">
            <?php if ($total > 0): ?>

                <div class="galeria-masonry" id="galeriaGrid">
                    <?php foreach ($fotos as $i => $foto): ?>
                        <div class="galeria-item" data-index="<?= $i ?>">
                            <img
                                    src="<?= htmlspecialchars($foto['web']) ?>"
                                    alt="Proyecto <?= $i + 1 ?>"
                                    loading="lazy"
                            >
                            <div class="galeria-overlay">
                                <div class="galeria-overlay-icon">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M15 3h6v6h-2V5h-4V3zM9 3H3v6h2V5h4V3zM15 21h4v-4h2v6h-6v-2zM9 21v2H3v-6h2v4h4z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="galeria-vacia">
                    <div class="galeria-vacia-icon">📁</div>
                    <h3>Aún no hay fotos</h3>
                    <p>Crea la carpeta <code>/img/galeria/</code> y sube imágenes ahí.</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- LIGHTBOX -->
        <div class="lightbox" id="lightbox">
            <button class="lightbox-close" id="lightboxClose" aria-label="Cerrar">&times;</button>
            <button class="lightbox-nav prev" id="lightboxPrev" aria-label="Anterior">&#8249;</button>

            <div class="lightbox-img-wrap">
                <img src="" alt="" id="lightboxImg">
                <div class="lightbox-counter">
                    <span id="lightboxNum">1</span> / <?= $total ?>
                </div>
                <!-- Tira de miniaturas -->
                <div class="lightbox-thumbs" id="lightboxThumbs">
                    <?php foreach ($fotos as $i => $foto): ?>
                        <div class="lb-thumb" data-index="<?= $i ?>">
                            <img src="<?= htmlspecialchars($foto['web']) ?>" alt="Miniatura <?= $i+1 ?>" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="lightbox-nav next" id="lightboxNext" aria-label="Siguiente">&#8250;</button>
        </div>
        <div class="lightbox-backdrop" id="lightboxBackdrop"></div>

        <!-- CTA FINAL -->
        <section class="galeria-cta">
            <div class="galeria-cta-inner">
                <h2>¿Quieres un resultado así para tu vehículo?</h2>
                <p>Solicita tu presupuesto sin compromiso y nuestro equipo te asesorará.</p>
                <a href="/src/views/presupuesto.php" class="btn-cta-galeria">Pedir presupuesto</a>
            </div>
        </section>

    </main>

    <script>
        const fotos   = <?= json_encode(array_column($fotos, 'web')) ?>;
        const items   = document.querySelectorAll('.galeria-item');
        const thumbs  = document.querySelectorAll('.lb-thumb');

        const lightbox         = document.getElementById('lightbox');
        const lightboxBackdrop = document.getElementById('lightboxBackdrop');
        const lightboxImg      = document.getElementById('lightboxImg');
        const lightboxNum      = document.getElementById('lightboxNum');
        const lightboxClose    = document.getElementById('lightboxClose');
        const lightboxPrev     = document.getElementById('lightboxPrev');
        const lightboxNext     = document.getElementById('lightboxNext');
        const lightboxThumbs   = document.getElementById('lightboxThumbs');

        let actual = 0;

        function abrirLightbox(i) {
            actual = i;
            actualizarLightbox();
            lightbox.classList.add('activo');
            lightboxBackdrop.classList.add('activo');
            document.body.style.overflow = 'hidden';
        }

        function cerrarLightbox() {
            lightbox.classList.remove('activo');
            lightboxBackdrop.classList.remove('activo');
            document.body.style.overflow = '';
        }

        function actualizarLightbox() {
            // Imagen grande
            lightboxImg.style.opacity = '0';
            setTimeout(() => {
                lightboxImg.src = fotos[actual];
                lightboxImg.style.opacity = '1';
            }, 180);
            lightboxNum.textContent = actual + 1;

            // Miniaturas: resaltar la activa y hacer scroll hacia ella
            thumbs.forEach((t, i) => {
                t.classList.toggle('activa', i === actual);
            });
            const thumbActiva = thumbs[actual];
            if (thumbActiva) {
                thumbActiva.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        }

        function cambiarFoto(dir) {
            actual = (actual + dir + fotos.length) % fotos.length;
            actualizarLightbox();
        }

        // Abrir desde el grid
        items.forEach(item => {
            item.addEventListener('click', () => abrirLightbox(+item.dataset.index));
        });

        // Clic en miniatura
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                actual = +thumb.dataset.index;
                actualizarLightbox();
            });
        });

        lightboxClose.addEventListener('click', cerrarLightbox);
        lightboxBackdrop.addEventListener('click', cerrarLightbox);
        lightboxPrev.addEventListener('click', () => cambiarFoto(-1));
        lightboxNext.addEventListener('click', () => cambiarFoto(1));

        document.addEventListener('keydown', e => {
            if (!lightbox.classList.contains('activo')) return;
            if (e.key === 'Escape')     cerrarLightbox();
            if (e.key === 'ArrowLeft')  cambiarFoto(-1);
            if (e.key === 'ArrowRight') cambiarFoto(1);
        });
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>