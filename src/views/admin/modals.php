<?php
/**
 * modal_responder_ticket.php
 *
 * Modal que aparece al hacer clic en "✉️ Responder" desde tab_tickets.php
 * Permite al admin:
 *   1. Asignar un empleado al ticket
 *   2. Indicar el presupuesto
 *   3. Escribir la descripción del trabajo
 *   4. Enviar automáticamente un correo al cliente (PHPMailer via panel.php)
 *
 * Variables esperadas:
 * @var array $empleados   [['id_usuario'=>..., 'Nombre'=>..., 'Apellido'=>...], ...]
 * @var array $respuestasPorSolicitud
 */ ?>

<!-- Backdrop -->
<div class="modal-backdrop" id="modalResponderBackdrop" onclick="cerrarModalResponder()"></div>
<div class="modal-backdrop" id="modalRespuestasBackdrop" onclick="cerrarModalRespuestas()"></div>
<div class="modal-backdrop" id="modalVerNotasBackdrop" onclick="cerrarModalVerNotas()"></div>
<div class="modal-backdrop" id="modalImagenesBackdrop" onclick="cerrarModalImagenes()"></div>


<!-- Modal Imágenes del Ticket -->
<div class="modal modal-imagenes" id="modalImagenes">
    <div class="modal-header">
        <h3 id="modalImagenesTitulo">🖼 Imágenes del Ticket</h3>
        <button onclick="cerrarModalImagenes()" class="modal-close">&times;</button>
    </div>
    <div class="modal-body">
        <div id="modalImagenesContenido" class="imagenes-grid"></div>
        <div class="modal-footer-btns" style="margin-top:1rem">
            <button onclick="cerrarModalImagenes()" class="btn-sm btn-gris">Cerrar</button>
        </div>
    </div>
</div>

<!-- Lightbox para imagen ampliada -->
<div class="modal-backdrop" id="lightboxBackdrop" onclick="cerrarLightbox()" style="z-index:1100"></div>
<div id="lightbox" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);
     z-index:1200;max-width:92vw;max-height:92vh;text-align:center">
    <img id="lightboxImg" src="" alt="Imagen ampliada"
         style="max-width:100%;max-height:88vh;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.6)">
    <div style="margin-top:.5rem">
        <a id="lightboxDescarga" href="" download class="btn-sm btn-azul" style="text-decoration:none">
            ⬇ Descargar
        </a>
        <button onclick="cerrarLightbox()" class="btn-sm btn-gris" style="margin-left:.4rem">Cerrar</button>
    </div>
</div>

<!-- Modal Respuestas -->
<div class="modal" id="modalRespuestas">
    <div class="modal-header">
        <h3 id="modalRespuestasTitulo">Respuestas</h3>
        <button onclick="cerrarModalRespuestas()" class="modal-close">&times;</button>
    </div>
    <div class="modal-body">
        <div id="modalRespuestasContenido"></div>
        <div class="modal-footer-btns" style="margin-top:1rem">
            <button onclick="cerrarModalRespuestas()" class="btn-sm btn-gris">Cerrar</button>
        </div>
    </div>
</div>
<!-- Modal Ver Notas -->
<div class="modal" id="modalVerNotas">
    <div class="modal-header">
        <h3>📋 Historial de notas</h3>
        <button onclick="cerrarModalVerNotas()" class="modal-close">&times;</button>
    </div>
    <div class="modal-body">
        <div id="cuerpoVerNotas"></div>
        <div class="modal-footer-btns" style="margin-top:1rem">
            <button onclick="cerrarModalVerNotas()" class="btn-sm btn-gris">Cerrar</button>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal" id="modalResponder">
    <div class="modal-header">
        <h3 id="modalResponderTitulo">Responder Ticket</h3>
        <button onclick="cerrarModalResponder()" class="modal-close">&times;</button>
    </div>

    <div class="modal-body">
        <form method="POST" id="formResponderTicket">
            <input type="hidden" name="accion"          value="responder_ticket">
            <input type="hidden" name="id_ticket"       id="responder_id_ticket">
            <input type="hidden" name="correo_cliente"  id="responder_correo_cliente">

            <!-- Asignar empleado -->
            <div class="form-group">
                <label for="responder_id_empleado">Asignar empleado</label>
                <select name="id_empleado" id="responder_id_empleado" class="select-rol w-full" required>
                    <option value="">— Selecciona un empleado —</option>
                    <?php foreach ($empleados as $emp): ?>
                        <option value="<?= (int)$emp['id_usuario'] ?>">
                            <?= htmlspecialchars($emp['Nombre'] . ' ' . $emp['Apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Presupuesto -->
            <div class="form-group">
                <label for="responder_presupuesto">Presupuesto (€)</label>
                <input type="number"
                       name="presupuesto"
                       id="responder_presupuesto"
                       class="input-field"
                       min="0"
                       step="0.01"
                       placeholder="Ej: 450.00"
                       required>
            </div>

            <!-- Descripción del trabajo -->
            <div class="form-group">
                <label for="responder_descripcion_trabajo">Descripción del trabajo</label>
                <textarea name="descripcion_trabajo"
                          id="responder_descripcion_trabajo"
                          class="textarea-modal"
                          rows="5"
                          placeholder="Describe el trabajo a realizar, piezas necesarias, plazos…"
                          required></textarea>
            </div>

            <!-- Info: se enviará correo -->
            <p class="modal-info">
                📧 Se enviará un correo automático al cliente con el presupuesto y la descripción.
            </p>

            <div class="modal-footer-btns">
                <button type="button" onclick="cerrarModalResponder()" class="btn-sm btn-gris">
                    Cancelar
                </button>
                <button type="submit" class="btn-sm btn-azul">
                    ✅ Guardar y enviar correo
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Backdrop -->
<div class="modal-backdrop" id="modalResponderSolicitudBackdrop" onclick="cerrarModalResponderSolicitud()"></div>
<!-- Backdrop -->
<div class="modal-backdrop" id="modalRespuestasSolicitudBackdrop" onclick="cerrarModalRespuestasSolicitud()"></div>

<!-- Modal Ver Respuestas -->
<div class="modal" id="modalRespuestasSolicitud">
    <div class="modal-header">
        <h3 id="modalRespuestasSolicitudTitulo">Respuestas</h3>
        <button onclick="cerrarModalRespuestasSolicitud()" class="modal-close">&times;</button>
    </div>
    <div class="modal-body">
        <div id="modalRespuestasSolicitudContenido"></div>
        <div class="modal-footer-btns" style="margin-top:1rem">
            <button onclick="cerrarModalRespuestasSolicitud()" class="btn-sm btn-gris">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal Responder -->
<div class="modal" id="modalResponderSolicitud">
    <div class="modal-header">
        <h3 id="modalResponderSolicitudTitulo">Responder Solicitud</h3>
        <button onclick="cerrarModalResponderSolicitud()" class="modal-close">&times;</button>
    </div>

    <div class="modal-body">
        <form method="POST" id="formResponderSolicitud">
            <input type="hidden" name="accion"         value="responder_solicitud">
            <input type="hidden" name="id_solicitud"   id="solicitud_id_solicitud">
            <input type="hidden" name="correo_cliente" id="solicitud_correo_cliente">

            <!-- Respuesta -->
            <div class="form-group">
                <label for="solicitud_respuesta">Respuesta</label>
                <textarea name="respuesta"
                          id="solicitud_respuesta"
                          class="textarea-modal"
                          rows="5"
                          placeholder="Escribe tu respuesta al cliente…"
                          required></textarea>
            </div>

            <p class="modal-info">
                📧 Se enviará un correo automático al cliente con tu respuesta.
            </p>

            <div class="modal-footer-btns">
                <button type="button" onclick="cerrarModalResponderSolicitud()" class="btn-sm btn-gris">
                    Cancelar
                </button>
                <button type="submit" class="btn-sm btn-azul">
                    ✅ Guardar y enviar correo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── Script solicitudes ───────────────────────────────────────── -->
<script>
    const respuestasPorSolicitud = <?= json_encode($respuestasPorSolicitud) ?>;


    function abrirModalRespuestasSolicitud(idSolicitud) {
        const respuestas = respuestasPorSolicitud[idSolicitud] || [];
        const contenido  = document.getElementById('modalRespuestasSolicitudContenido');

        contenido.innerHTML = respuestas.length === 0
            ? '<p style="color:#9ca3af">Sin respuestas aún.</p>'
            : respuestas.map(r => `
            <div class="nota-admin-item">
                <span style="color:#9ca3af;font-size:.75rem;">${r.fecha_respuesta}</span>
                <p style="margin:.25rem 0 0;color:#374151;white-space:pre-wrap">${r.respuesta}</p>
            </div>`).join('');

        document.getElementById('modalRespuestasSolicitudTitulo').textContent = `Respuestas de la Solicitud #${idSolicitud}`;
        document.getElementById('modalRespuestasSolicitud').classList.add('activo');
        document.getElementById('modalRespuestasSolicitudBackdrop').classList.add('activo');
    }

    function cerrarModalRespuestasSolicitud() {
        document.getElementById('modalRespuestasSolicitud').classList.remove('activo');
        document.getElementById('modalRespuestasSolicitudBackdrop').classList.remove('activo');
    }

    function abrirModalResponderSolicitud(idSolicitud, cliente, correo) {
        document.getElementById('solicitud_id_solicitud').value              = idSolicitud;
        document.getElementById('solicitud_correo_cliente').value            = correo;
        document.getElementById('modalResponderSolicitudTitulo').textContent = `Responder Solicitud #${idSolicitud} — ${cliente}`;

        document.getElementById('modalResponderSolicitud').classList.add('activo');
        document.getElementById('modalResponderSolicitudBackdrop').classList.add('activo');
    }

    function abrirModalImagenes(btn) {
        const docs     = JSON.parse(btn.getAttribute('data-docs') || '[]');
        const idTicket = btn.getAttribute('data-ticket');
        const contenido = document.getElementById('modalImagenesContenido');

        document.getElementById('modalImagenesTitulo').textContent = `🖼 Imágenes del Ticket #${idTicket}`;

        if (docs.length === 0) {
            contenido.innerHTML = '<p style="color:#9ca3af">Sin imágenes adjuntas.</p>';
        } else {
            contenido.innerHTML = docs.map(d => `
                <div class="imagen-card" onclick="abrirLightbox('${escHtml(d.ruta)}', '${escHtml(d.nombre)}')">
                    <img src="${escHtml(d.ruta)}" alt="${escHtml(d.nombre)}"
                         onerror="this.parentElement.classList.add('imagen-error');this.style.display='none'">
                    <span class="imagen-nombre">${escHtml(d.nombre)}</span>
                    <span class="imagen-zoom">🔍 Ver</span>
                </div>
            `).join('');
        }

        document.getElementById('modalImagenes').classList.add('activo');
        document.getElementById('modalImagenesBackdrop').classList.add('activo');
    }

    function cerrarModalImagenes() {
        document.getElementById('modalImagenes').classList.remove('activo');
        document.getElementById('modalImagenesBackdrop').classList.remove('activo');
    }

    function abrirLightbox(ruta, nombre) {
        document.getElementById('lightboxImg').src         = ruta;
        document.getElementById('lightboxDescarga').href   = ruta;
        document.getElementById('lightboxDescarga').download = nombre;
        document.getElementById('lightbox').style.display  = 'block';
        document.getElementById('lightboxBackdrop').classList.add('activo');
    }

    function cerrarLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.getElementById('lightboxBackdrop').classList.remove('activo');
        document.getElementById('lightboxImg').src = '';
    }

    function cerrarModalResponderSolicitud() {
        document.getElementById('modalResponderSolicitud').classList.remove('activo');
        document.getElementById('modalResponderSolicitudBackdrop').classList.remove('activo');
    }
</script>

<!-- ── Estilos del modal ─────────────────────────────────────────── -->
<style>
    /* Backdrop */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 900;
    }
    .modal-backdrop.activo { display: block; }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        width: min(520px, 95vw);
        max-height: 90vh;
        overflow-y: auto;
        z-index: 1000;
        animation: modalIn .22s ease;
    }
    .modal.activo { display: block; }

    @keyframes modalIn {
        from { opacity:0; transform:translate(-50%,-48%); }
        to   { opacity:1; transform:translate(-50%,-50%); }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .modal-header h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #9ca3af;
        line-height: 1;
        padding: 0;
    }
    .modal-close:hover { color: #111827; }

    .modal-body { padding: 1.5rem; }

    /* Grupos de formulario */
    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
        display: block;
        font-size: .85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .4rem;
    }
    .input-field,
    .textarea-modal,
    .select-rol {
        width: 100%;
        padding: .55rem .75rem;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        font-size: .9rem;
        font-family: inherit;
        color: #111827;
        background: #f9fafb;
        box-sizing: border-box;
        transition: border-color .2s;
    }
    .input-field:focus,
    .textarea-modal:focus,
    .select-rol:focus {
        outline: none;
        border-color: #3b82f6;
        background: #fff;
    }
    .textarea-modal { resize: vertical; }

    /* Info note */
    .modal-info {
        font-size: .82rem;
        color: #6b7280;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 7px;
        padding: .6rem .9rem;
        margin-bottom: 1.2rem;
    }

    /* Botones footer */
    .modal-footer-btns {
        display: flex;
        justify-content: flex-end;
        gap: .6rem;
    }

    /* Clase utilitaria */
    .w-full { width: 100%; }

    /* Modal imágenes — más ancho para la galería */
    .modal-imagenes {
        width: min(720px, 96vw);
    }

    /* Grid de imágenes */
    .imagenes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .imagen-card {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        background: #f9fafb;
        transition: transform .15s, box-shadow .15s;
    }
    .imagen-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,.12);
    }
    .imagen-card img {
        width: 100%;
        height: 130px;
        object-fit: cover;
        display: block;
    }
    .imagen-nombre {
        display: block;
        font-size: .72rem;
        color: #6b7280;
        padding: 4px 6px 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .imagen-zoom {
        display: block;
        font-size: .75rem;
        color: #3b82f6;
        padding: 0 6px 6px;
        font-weight: 600;
    }
    .imagen-error {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 130px;
        color: #9ca3af;
        font-size: .8rem;
    }
    .imagen-error::before { content: '⚠ No disponible'; }
</style>