<?php /** @var array $tickets @var array $colores_estado @var array $notasPorTicket @var array $respuestasPorTicket @var array $documentosPorTicket */ ?>

<section class="tab-content" id="tab-tickets">
    <h1 class="admin-titulo">Gestión de Tickets</h1>
    <input type="text"
           id="buscadorTickets"
           placeholder="🔍 Buscar por cliente, matrícula o estado…"
           class="buscador-input"
           oninput="filtrarTablaTickets()">

    <div class="tabla-wrapper">
        <table class="admin-tabla" id="tablaTickets">
            <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Matrícula</th>
                <th>Fecha inicio</th>
                <th>Presupuesto</th>
                <th>Empleado asignado</th>
                <th>Estado</th>
                <th>Cambiar estado</th>
                <th>Notas</th>
                <th>Acciones</th>
                <th>Imagen</th>
                <th>Respuesta</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $t): ?>
                <?php
                // Ahora usas los arrays ya preparados por el controlador
                $notas      = $notasPorTicket[$t['id_ticket']]      ?? [];
                $respuestas = $respuestasPorTicket[$t['id_ticket']] ?? [];
                $documentos = $documentosPorTicket[$t['id_ticket']] ?? [];
                ?>
                
                <tr>
                    <td>#<?= (int)$t['id_ticket'] ?></td>
                    <td>
                        <?= htmlspecialchars($t['Nombre'] . ' ' . $t['Apellido']) ?>
                        <br><small style="color:#999"><?= htmlspecialchars($t['Correo']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($t['servicio'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($t['matricula'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($t['fecha_inicio'] ?? '—') ?></td>
                    <td><?= $t['presupuesto'] ? number_format((float)$t['presupuesto'], 2) . ' €' : '—' ?></td>

                    <td>
                        <?php if (!empty($t['empleado_nombre'])): ?>
                            <?= htmlspecialchars($t['empleado_nombre'] . ' ' . $t['empleado_apellido']) ?>
                        <?php else: ?>
                            <span style="color:#9ca3af;font-style:italic;">Sin asignar</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <span class="badge-estado"
                              style="background:<?= $colores_estado[$t['estado']] ?? '#999' ?>">
                            <?= htmlspecialchars($t['estado']) ?>
                        </span>
                    </td>

                    <td>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="accion"    value="cambiar_estado_ticket">
                            <input type="hidden" name="id_ticket" value="<?= (int)$t['id_ticket'] ?>">
                            <select name="estado" class="select-rol">
                                <option value="pendiente"  <?= $t['estado']==='pendiente'  ? 'selected':'' ?>>pendiente</option>
                                <option value="en proceso" <?= $t['estado']==='en proceso' ? 'selected':'' ?>>en proceso</option>
                                <option value="completado" <?= $t['estado']==='completado' ? 'selected':'' ?>>completado</option>
                                <option value="cancelado"  <?= $t['estado']==='cancelado'  ? 'selected':'' ?>>cancelado</option>
                            </select>
                            <button type="submit" class="btn-sm btn-azul">Guardar</button>
                        </form>
                    </td>

                    <td>
                        <?php if (!empty($notas)): ?>
                            <?php $notasJson = htmlspecialchars(json_encode($notas), ENT_QUOTES, 'UTF-8') ?>
                            <button class="btn-sm btn-gris"
                                    data-notas="<?= $notasJson ?>"
                                    onclick="abrirModalVerNotas(this)">
                                📋 Ver notas (<?= count($notas) ?>)
                            </button>
                        <?php else: ?>
                            <span style="color:#9ca3af;font-size:.8rem;">Sin notas</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <button class="btn-sm btn-verde"
                                onclick="abrirModalResponder(
                                <?= (int)$t['id_ticket'] ?>,
                                        '<?= htmlspecialchars($t['Nombre'] . ' ' . $t['Apellido'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($t['Correo'], ENT_QUOTES) ?>',
                                <?= (int)($t['id_empleado'] ?? 0) ?>,
                                <?= $t['presupuesto'] ? (float)$t['presupuesto'] : 0 ?>,
                                        '<?= htmlspecialchars($t['descripcion_trabajo'] ?? '', ENT_QUOTES) ?>'
                                        )">
                            ✉️ Responder
                        </button>
                    </td>

                    <td>
                        <?php if (!empty($documentos)): ?>
                            <?php $docsJson = htmlspecialchars(json_encode($documentos), ENT_QUOTES, 'UTF-8') ?>
                            <button class="btn-sm btn-gris"
                                    data-docs="<?= $docsJson ?>"
                                    data-ticket="<?= (int)$t['id_ticket'] ?>"
                                    onclick="abrirModalImagenes(this)">
                                👁 Ver (<?= count($documentos) ?>)
                            </button>
                        <?php else: ?>
                            <span style="color:#9ca3af;font-size:.8rem;">Sin imágenes</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if (!empty($respuestas)): ?>
                            <button class="btn-sm btn-gris"
                                    onclick="abrirModalRespuestas(<?= (int)$t['id_ticket'] ?>)">
                                👁 Ver (<?= count($respuestas) ?>)
                            </button>
                        <?php else: ?>
                            <span style="color:#9ca3af;font-size:.8rem;">Sin respuestas</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginacion" id="paginacionTickets"></div>
</section>

<script>
    // Ahora el JSON viene del array ya preparado por el controlador
    const respuestasPorTicket = <?= json_encode($respuestasPorTicket) ?>;

    function abrirModalRespuestas(idTicket) {
        const respuestas = respuestasPorTicket[idTicket] || [];
        const contenido  = document.getElementById('modalRespuestasContenido');

        contenido.innerHTML = respuestas.length === 0
            ? '<p style="color:#9ca3af">Sin respuestas aún.</p>'
            : respuestas.map(r => `
                <div class="nota-admin-item">
                    <span style="color:#9ca3af;font-size:.75rem;">${r.fecha_respuesta}</span>
                    <p style="margin:.25rem 0 0;white-space:pre-wrap">${r.respuesta}</p>
                </div>`).join('');

        document.getElementById('modalRespuestasTitulo').textContent = `Respuestas del Ticket #${idTicket}`;
        document.getElementById('modalRespuestas').classList.add('activo');
        document.getElementById('modalRespuestasBackdrop').classList.add('activo');
    }

    function cerrarModalRespuestas() {
        document.getElementById('modalRespuestas').classList.remove('activo');
        document.getElementById('modalRespuestasBackdrop').classList.remove('activo');
    }

    function filtrarTablaTickets() {
        const q = document.getElementById('buscadorTickets').value.toLowerCase();
        document.querySelectorAll('#tablaTickets tbody tr').forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    function abrirModalResponder(idTicket, cliente, correo, idEmpleadoActual, presupuesto, descTrabajo) {
        document.getElementById('responder_id_ticket').value           = idTicket;
        document.getElementById('responder_correo_cliente').value      = correo;
        document.getElementById('responder_presupuesto').value         = presupuesto || '';
        document.getElementById('responder_descripcion_trabajo').value = descTrabajo || '';
        document.getElementById('modalResponderTitulo').textContent    = `Responder Ticket #${idTicket} — ${cliente}`;

        const sel = document.getElementById('responder_id_empleado');
        if (sel && idEmpleadoActual) {
            for (let opt of sel.options) opt.selected = (parseInt(opt.value) === idEmpleadoActual);
        }

        document.getElementById('modalResponder').classList.add('activo');
        document.getElementById('modalResponderBackdrop').classList.add('activo');
    }

    function cerrarModalResponder() {
        document.getElementById('modalResponder').classList.remove('activo');
        document.getElementById('modalResponderBackdrop').classList.remove('activo');
    }
    function abrirModalVerNotas(btn) {
        const notas  = JSON.parse(btn.getAttribute('data-notas') || '[]');
        const cuerpo = document.getElementById('cuerpoVerNotas');

        cuerpo.innerHTML = notas.length === 0
            ? '<p style="color:#9ca3af">Sin notas registradas.</p>'
            : notas.map(n => `
            <div class="nota-admin-item">
                <strong>${escHtml(n.Nombre ?? '')} ${escHtml(n.Apellido ?? '')}</strong>
                <span style="color:#9ca3af;font-size:.75rem;margin-left:.5rem;">${escHtml(n.fecha ?? '')}</span>
                <p style="margin:.25rem 0 0">${escHtml(n.nota ?? '').replace(/\n/g,'<br>')}</p>
            </div>`).join('');

        document.getElementById('modalVerNotas').classList.add('activo');
        document.getElementById('modalVerNotasBackdrop').classList.add('activo');
    }

    function cerrarModalVerNotas() {
        document.getElementById('modalVerNotas').classList.remove('activo');
        document.getElementById('modalVerNotasBackdrop').classList.remove('activo');
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
    const TICKETS_POR_PAGINA = 20;
    let paginaActual = 1;

    function iniciarPaginacion() {
        const filas = Array.from(document.querySelectorAll('#tablaTickets tbody tr'));
        const total = Math.ceil(filas.length / TICKETS_POR_PAGINA);

        function mostrarPagina(pagina) {
            paginaActual = pagina;
            filas.forEach((fila, i) => {
                fila.style.display = (i >= (pagina - 1) * TICKETS_POR_PAGINA && i < pagina * TICKETS_POR_PAGINA) ? '' : 'none';
            });
            renderBotones(total);
        }

        function renderBotones(total) {
            const contenedor = document.getElementById('paginacionTickets');
            contenedor.innerHTML = '';

            if (paginaActual > 1) {
                const anterior = document.createElement('button');
                anterior.textContent = '← Anterior';
                anterior.className = 'btn-sm btn-gris';
                anterior.onclick = () => mostrarPagina(paginaActual - 1);
                contenedor.appendChild(anterior);
            }

            for (let i = 1; i <= total; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = 'btn-sm ' + (i === paginaActual ? 'btn-azul' : 'btn-gris');
                btn.onclick = () => mostrarPagina(i);
                contenedor.appendChild(btn);
            }

            if (paginaActual < total) {
                const siguiente = document.createElement('button');
                siguiente.textContent = 'Siguiente →';
                siguiente.className = 'btn-sm btn-gris';
                siguiente.onclick = () => mostrarPagina(paginaActual + 1);
                contenedor.appendChild(siguiente);
            }
        }

        mostrarPagina(1);
    }

    // Reinicia paginación cuando se busca
    const _filtrarTicketsOriginal = filtrarTablaTickets;
    filtrarTablaTickets = function() {
        _filtrarTicketsOriginal();
        // Muestra todas las que coinciden sin paginar durante búsqueda
        const q = document.getElementById('buscadorTickets').value.toLowerCase();
        if (!q) iniciarPaginacion();
    }

    document.addEventListener('DOMContentLoaded', iniciarPaginacion);
</script>