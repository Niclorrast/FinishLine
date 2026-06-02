<?php
/**
 * Variables que recibe desde panel.php:
 * @var array  $stats              ['mis_tickets', 'en_proceso', 'completados']
 * @var array  $tickets            Tickets asignados al empleado
 * @var array  $notasPorTicket     [id_ticket => array de notas]
 * @var array  $colores_estado
 */ ?>
<section class="tab-content activo" id="tab-empleado">
    <h1 class="admin-titulo">Mis tickets asignados</h1>

    <!-- estadisticas -->
    <div class="stats-grid">
        <div class="stat-card destacada">
            <div class="stat-numero"><?= (int)$stats['mis_tickets'] ?></div>
            <div class="stat-label">Mis tickets</div>
        </div>
        <div class="stat-card azul">
            <div class="stat-numero"><?= (int)$stats['en_proceso'] ?></div>
            <div class="stat-label">En proceso</div>
        </div>
        <div class="stat-card verde">
            <div class="stat-numero"><?= (int)$stats['completados'] ?></div>
            <div class="stat-label">Completados</div>
        </div>
    </div>

    <!-- Barra de buscar -->
    <input type="text"
           id="buscadorEmpleado"
           placeholder="🔍 Buscar por cliente, matrícula o estado…"
           class="buscador-input"
           oninput="filtrarTablaEmpleado()">

    <div class="tabla-wrapper">
        <table class="admin-tabla" id="tablaEmpleado">
            <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Matrícula</th>
                <th>Modelo</th>
                <th>Fecha inicio</th>
                <th>Presupuesto</th>
                <th>Estado</th>
                <th>Cambiar estado</th>
                <th>Notas</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;padding:2rem;color:#6b7280;">
                        No tienes tickets asignados aún.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($tickets as $t): ?>
                    <?php $notas = $notasPorTicket[$t['id_ticket']] ?? []; ?>
                    <tr>
                        <td>#<?= (int)$t['id_ticket'] ?></td>
                        <td>
                            <?= htmlspecialchars($t['Nombre'] . ' ' . $t['Apellido']) ?>
                            <br><small style="color:#999"><?= htmlspecialchars($t['Correo']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($t['servicio'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($t['matricula'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($t['modelo_auto'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($t['fecha_inicio'] ?? '—') ?></td>
                        <td><?= $t['presupuesto'] ? number_format((float)$t['presupuesto'], 2) . ' €' : '—' ?></td>
                        <td>
                            <span class="badge-estado"
                                  style="background:<?= $colores_estado[$t['estado']] ?? '#999' ?>">
                                <?= htmlspecialchars($t['estado']) ?>
                            </span>
                        </td>

                        <!-- Cambiar estado -->
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

                        <!-- Celda de notas: escribir + botón modal -->
                        <td class="celda-notas">
                            <form method="POST" class="form-notas">
                                <input type="hidden" name="accion"    value="guardar_nota">
                                <input type="hidden" name="id_ticket" value="<?= (int)$t['id_ticket'] ?>">
                                <textarea required
                                        name="nota"
                                        class="textarea-nota"
                                        rows="2"
                                        placeholder="Nueva nota…"></textarea>
                                <div class="notas-acciones">
                                    <button type="submit" class="btn-sm btn-verde">💾 Guardar nota</button>
                                    <?php if (!empty($notas)): ?>
                                        <button
                                                type="button"
                                                class="btn-sm btn-notas"
                                                onclick="abrirModalNotas(<?= (int)$t['id_ticket'] ?>)">
                                            📋 Notas
                                            <span class="notas-badge"><?= count($notas) ?></span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>

                            <!-- Datos de notas para el modal (oculto) -->
                            <script type="application/json" id="notas-<?= (int)$t['id_ticket'] ?>">
                                <?= json_encode(array_reverse($notas), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
                            </script>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ═══════════════════════════════════════
     MODAL DE NOTAS
═══════════════════════════════════════ -->
<div id="modalNotas" class="modal-notas-overlay" onclick="cerrarModalNotas(event)">
    <div class="modal-notas-box">
        <div class="modal-notas-header">
            <h3>📋 Historial de notas — Ticket <span id="modalTicketId"></span></h3>
            <button class="modal-notas-close" onclick="cerrarModalNotas()">&times;</button>
        </div>
        <div class="modal-notas-body" id="modalNotasBody">
            <!-- Se rellena con JS -->
        </div>
    </div>
</div>


<!-- ── Estilos ── -->
<style>
    /* Stats verde */
    .stat-card.verde { border-top: 3px solid #10b981; }
    .stat-card.verde .stat-numero { color: #10b981; }

    /* Celda notas */
    .celda-notas { min-width: 200px; }

    .textarea-nota {
        width: 100%;
        padding: .45rem .6rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: .82rem;
        font-family: inherit;
        resize: vertical;
        background: #f9fafb;
        color: #111827;
        transition: border-color .2s;
        box-sizing: border-box;
        display: block;
        margin-bottom: .4rem;
    }
    .textarea-nota:focus {
        outline: none;
        border-color: #3b82f6;
        background: #fff;
    }

    .notas-acciones {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-verde {
        background: #10b981;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 6px;
        padding: .3rem .75rem;
        font-size: .8rem;
        transition: background .2s;
        white-space: nowrap;
    }
    .btn-verde:hover { background: #059669; }

    /* Botón "Notas" con badge */
    .btn-notas {
        background: #f0f9ff;
        color: #0369a1;
        border: 1.5px solid #bae6fd;
        cursor: pointer;
        border-radius: 6px;
        padding: .3rem .7rem;
        font-size: .8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background .2s, border-color .2s;
        white-space: nowrap;
    }
    .btn-notas:hover { background: #e0f2fe; border-color: #7dd3fc; }

    .notas-badge {
        background: #0369a1;
        color: #fff;
        border-radius: 10px;
        padding: 1px 7px;
        font-size: .72rem;
        font-weight: 700;
        line-height: 1.4;
    }

    /* ── Modal overlay ── */
    .modal-notas-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-notas-overlay.abierto { display: flex; }

    /* ── Modal caja ── */
    .modal-notas-box {
        background: #fff;
        border-radius: 12px;
        width: min(520px, 92vw);
        max-height: 78vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0,0,0,.3);
        animation: modalIn .22s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(.94) translateY(12px); }
        to   { opacity: 1; transform: scale(1)  translateY(0); }
    }

    .modal-notas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid #e5e7eb;
    }
    .modal-notas-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .modal-notas-close {
        background: none;
        border: none;
        font-size: 22px;
        color: #6b7280;
        cursor: pointer;
        line-height: 1;
        padding: 0 4px;
        transition: color .2s;
    }
    .modal-notas-close:hover { color: #ef4444; }

    .modal-notas-body {
        overflow-y: auto;
        padding: 18px 22px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* ── Cada nota en el modal ── */
    .modal-nota-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 3px solid #3b82f6;
        border-radius: 8px;
        padding: 10px 14px;
    }
    .modal-nota-fecha {
        font-size: .75rem;
        color: #6b7280;
        margin-bottom: 4px;
        display: block;
    }
    .modal-nota-texto {
        font-size: .87rem;
        color: #1f2937;
        line-height: 1.55;
        margin: 0;
        white-space: pre-wrap;
    }

    .modal-notas-vacio {
        text-align: center;
        padding: 30px 0;
        color: #9ca3af;
        font-size: .9rem;
    }
</style>

<!-- ── JS ── -->
<script>
    // Filtro buscador
    function filtrarTablaEmpleado() {
        const q     = document.getElementById('buscadorEmpleado').value.toLowerCase();
        const filas = document.querySelectorAll('#tablaEmpleado tbody tr');
        filas.forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    // Abrir modal de notas
    function abrirModalNotas(idTicket) {
        const modal  = document.getElementById('modalNotas');
        const body   = document.getElementById('modalNotasBody');
        const titulo = document.getElementById('modalTicketId');

        titulo.textContent = '#' + idTicket;

        // Leer notas del JSON embebido
        const dataEl = document.getElementById('notas-' + idTicket);
        let notas = [];
        if (dataEl) {
            try { notas = JSON.parse(dataEl.textContent); } catch(e) {}
        }

        // Renderizar
        if (notas.length === 0) {
            body.innerHTML = '<div class="modal-notas-vacio">Sin notas registradas aún.</div>';
        } else {
            body.innerHTML = notas.map(n => `
                <div class="modal-nota-item">
                    <span class="modal-nota-fecha">${escHtml(n.fecha ?? '')}</span>
                    <p class="modal-nota-texto">${escHtml(n.nota ?? '')}</p>
                </div>
            `).join('');
        }

        modal.classList.add('abierto');
        document.body.style.overflow = 'hidden';
    }

    // Cerrar modal
    function cerrarModalNotas(e) {
        // Si se pasó evento, solo cerrar si se clicó el overlay (no la caja)
        if (e && e.target !== document.getElementById('modalNotas')) return;
        document.getElementById('modalNotas').classList.remove('abierto');
        document.body.style.overflow = '';
    }

    // Escape cierra el modal
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('modalNotas').classList.remove('abierto');
            document.body.style.overflow = '';
        }
    });

    // Sanitizar HTML para evitar XSS
    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }
</script>