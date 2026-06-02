<?php /** @var array $stats @var array $tickets @var array $colores_estado */ ?>

<!-- Estadicticas del panel de control-->
<section class="tab-content activo" id="tab-dashboard">
    <h1 class="admin-titulo">Dashboard</h1>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-numero"><?= $stats['total_usuarios'] ?></div>
            <div class="stat-label">Usuarios totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-numero"><?= $stats['total_clientes'] ?></div>
            <div class="stat-label">Clientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-numero"><?= $stats['total_empleados'] ?></div>
            <div class="stat-label">Empleados</div>
        </div>
        <div class="stat-card destacada">
            <div class="stat-numero"><?= $stats['total_tickets'] ?></div>
            <div class="stat-label">Tickets totales</div>
        </div>
        <div class="stat-card amarilla">
            <div class="stat-numero"><?= $stats['tickets_pendientes'] ?></div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-card azul">
            <div class="stat-numero"><?= $stats['tickets_en_proceso'] ?></div>
            <div class="stat-label">En proceso</div>
        </div>
    </div>

    <!--Ultimos 5 tickets-->
    <h2 class="admin-subtitulo" >Últimos 5 tickets</h2>
    <div class="tabla-wrapper">
        <table class="admin-tabla">
            <thead>
            <tr><th>#</th><th>Cliente</th><th>Servicio</th><th>Matrícula</th><th>Estado</th><th>Presupuesto</th></tr>
            </thead>
            <tbody>
            <?php foreach (array_slice($tickets, 0, 5) as $t): ?>
                <tr>
                    <td>#<?= $t['id_ticket'] ?></td>
                    <td><?= htmlspecialchars($t['Nombre'] . ' ' . $t['Apellido']) ?></td>
                    <td><?= htmlspecialchars($t['servicio'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($t['matricula'] ?? '—') ?></td>
                    <td>
                        <span class="badge-estado" style="background:<?= $colores_estado[$t['estado']] ?? '#999' ?>">
                            <?= htmlspecialchars($t['estado']) ?>
                        </span>
                    </td>
                    <td><?= $t['presupuesto'] ? number_format($t['presupuesto'], 2) . ' €' : '—' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>