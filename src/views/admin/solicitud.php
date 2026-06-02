<?php
/**
 * solicitud.php
 * @var array $solicitudes
 * @var array $respuestasPorSolicitud
 */ ?>

<section class="tab-content" id="tab-solicitud">
    <h1 class="admin-titulo">Solicitudes de Contacto</h1>

    <div class="tabla-wrapper">
        <table class="admin-tabla">
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Asunto</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
                <th>Respuestas</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($solicitudes)): ?>
                <tr>
                    <td colspan="9" style="text-align:center;color:#9ca3af;">
                        No hay solicitudes aún.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($solicitudes as $s): ?>
                    <?php $yaRespondida = !empty($respuestasPorSolicitud[$s['id_solicitud']])
                            || $s['estado'] === 'atendida'; ?>
                    <tr>
                        <td>#<?= (int)$s['id_solicitud'] ?></td>
                        <td><?= htmlspecialchars($s['nombre']) ?></td>
                        <td><?= htmlspecialchars($s['correo']) ?></td>
                        <td><?= htmlspecialchars($s['asunto']) ?></td>
                        <td><?= htmlspecialchars($s['mensaje']) ?></td>
                        <td><?= htmlspecialchars($s['fecha_envio']) ?></td>
                        <td>
                            <?php
                            $colores = [
                                    'pendiente'  => '#f59e0b',
                                    'Respondida' => '#10b981',
                                    'cancelada'  => '#ef4444',
                            ];
                            $color = $colores[$s['estado']] ?? '#999';
                            ?>
                            <span class="badge-estado" style="background:<?= $color ?>">
                                <?= htmlspecialchars($s['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!$yaRespondida): ?>
                                <button class="btn-sm btn-verde"
                                        onclick="abrirModalResponderSolicitud(
                                        <?= (int)$s['id_solicitud'] ?>,
                                                '<?= htmlspecialchars($s['nombre'], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($s['correo'], ENT_QUOTES) ?>'
                                                )">
                                    ✉️ Responder
                                </button>
                            <?php else: ?>
                                <span style="color:#10b981;font-size:.8rem;">✔ Respondida</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-sm btn-azul"
                                    onclick="abrirModalRespuestasSolicitud(<?= (int)$s['id_solicitud'] ?>)">
                                👁 Ver
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>