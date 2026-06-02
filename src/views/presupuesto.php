<?php
require_once __DIR__ . '/../controllers/PresupuestoController.php';
// Protección: solo accesible desde el ViewController
if (!isset($listaServicios)) {
    header("Location: /src/views/presupuesto.php");
    exit();
} 

$servicio_id = $servicio_id ?? '';

require_once __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/src/styles/Contacto.css">
<link rel="stylesheet" href="/src/styles/Presupuesto.css">

<main class="contact-container">

    <div class="contact-header">
        <h1>Solicitud de <span>Presupuesto</span></h1>
        <p>Hola <strong><?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'cliente') ?></strong>, dinos qué necesita tu coche.</p>
    </div>

    <div class="contact-content">
        <?php include __DIR__ . '/../includes/info_contacto.php'; ?>

        <div class="contact-form-container">

            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <p class="msg-exito" style="color:green;text-align:center;">✅ Solicitud enviada correctamente.</p>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <p class="msg-error" style="color:red;text-align:center;">❌ Error al enviar la solicitud.</p>
            <?php endif; ?>

            <form action="/src/controllers/PresupuestoController.php" method="POST" enctype="multipart/form-data" class="modern-form">

                <div class="form-group">
                    <label>Servicio deseado</label>
                    <select name="servicio" id="servicio" required>
                        <option value="">-- Selecciona un servicio --</option>
                        <?php foreach ($listaServicios as $row): ?>
                            <option value="<?= $row['id_servicio'] ?>"
                                <?= ($servicio_id == $row['id_servicio']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['Nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Modelo del coche</label>
                    <input type="text" name="modelo_coche" placeholder="Marca y Modelo..." required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" placeholder="Explícanos los daños..." required></textarea>
                </div>

               <div class="form-group">
                    <label>Matricula</label>
                    <textarea name="matricula" placeholder="Dime la matrícula..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Adjuntar fotos (Opcional)</label>
                    <input type="file" name="foto_vehiculo[]" accept="image/*" multiple>
                </div>

                <button type="submit" class="btn-submit">Enviar Solicitud</button>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>