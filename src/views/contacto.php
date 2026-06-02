<?php
require_once __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/src/styles/Contacto.css">

<main class="contact-container">
    <div class="contact-header">
        <h1>Contacta con <span>Nosotros</span></h1>
        <div class="divider"></div>
        <p>¿Tienes un proyecto en mente? Nuestro equipo técnico está listo para asesorarte en acabados de alta resistencia.</p>
    </div>

    <div class="contact-content">

        <?php include __DIR__ . '/../includes/info_contacto.php'; ?>

        <div class="contact-form-container">
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div id="alert-box" class="alert alert-success" style="display:block;">
           ✅ Solicitud enviada correctamente.
        </div>
    <?php endif; ?>

            <div id="alert-box" class="alert" style="display:none;"></div>

            <!-- ✅ action corregido -->
            <form id="form-contacto" action="/src/controllers/ContactoController.php" method="POST">

                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre..." required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="asunto">Asunto</label>
                    <!-- ✅ opciones alineadas con la validación del controller -->
                    <select id="asunto" name="asunto">
                        <option value="tecnico">Asesoría Técnica</option>
                        
                        <option value="otros">Otros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5" maxlength="500"
                              placeholder="Cuéntanos sobre tu proyecto..." required></textarea>
                    <div id="char-count" class="char-counter">0 / 500 caracteres</div>
                </div>

                

                <button type="submit" class="btn-submit">Enviar Mensaje</button>

            </form>
        </div>
    </div>
</main>

<script src="/src/scripts/ContadorContacto.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>