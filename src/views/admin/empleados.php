<?php /** @var array $empleados @var array $tickets @var string $mensajeEmpleado @var string $tipoMensajeEmpleado */ ?>

<section class="tab-content" id="tab-empleados">
    <div class="empleados-header">
        <h1 class="admin-titulo">Empleados</h1>
        <button class="btn-principal" onclick="toggleFormEmpleado()">➕ Añadir empleado</button>
    </div>

    <!-- ── Flash message ─────────────────────────────────────────── -->
    <?php if (!empty($mensajeEmpleado)): ?>
        <div class="alerta alerta-<?= $tipoMensajeEmpleado ?>">
            <?= htmlspecialchars($mensajeEmpleado) ?>
        </div>
    <?php endif; ?>

    <!-- ── Formulario oculto ─────────────────────────────────────── -->
    <div class="card-form" id="formEmpleadoWrapper" style="display:none; margin-bottom:2rem;">
        <h2 style="margin:0 0 1.2rem;font-size:1.1rem;color:#1e3a5f;">Nuevo empleado</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="crear_empleado">

            <div class="form-fila">
                <div class="form-grupo">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required placeholder="Ej: María">
                </div>
                <div class="form-grupo">
                    <label>Apellido</label>
                    <input type="text" name="apellido" required placeholder="Ej: García">
                </div>
            </div>
            <div class="form-fila">
                <div class="form-grupo">
                    <label>Correo</label>
                    <input type="email" name="correo" required placeholder="empleado@empresa.com">
                </div>
                <div class="form-grupo">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" placeholder="612345678">
                </div>
            </div>
            <div class="form-fila">
                <div class="form-grupo">
                    <label>Contraseña temporal</label>
                    <input type="password" name="password" required
                           placeholder="Mínimo 6 caracteres" minlength="6">
                </div>
            </div>
            <div style="display:flex;gap:.8rem;margin-top:.5rem;">
                <button type="submit" class="btn-principal">✔ Crear empleado</button>
                <button type="button" class="btn-cancelar" onclick="toggleFormEmpleado()">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- ── Tabla de empleados ────────────────────────────────────── -->
    <div class="tabla-wrapper">
        <table class="admin-tabla">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Tickets asignados</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($empleados)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:#6b7280;">
                        No hay empleados registrados aún.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($empleados as $emp): ?>
                    <?php
                    $totalTickets = count(array_filter($tickets, fn($t) =>
                        (int)$t['id_empleado'] === (int)$emp['id_usuario']
                    ));
                    ?>
                    <tr>
                        <td><?= (int)$emp['id_usuario'] ?></td>
                        <td><?= htmlspecialchars($emp['Nombre'] . ' ' . $emp['Apellido']) ?></td>
                        <td><?= htmlspecialchars($emp['Correo'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($emp['Telefono'] ?? '—') ?></td>
                        <td>
                            <span class="badge-estado" style="background:#3b82f6">
                                <?= $totalTickets ?> ticket<?= $totalTickets !== 1 ? 's' : '' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars($emp['Nombre'], ENT_QUOTES) ?>?')">
                                <input type="hidden" name="accion"     value="eliminar_usuario">
                                <input type="hidden" name="id_usuario" value="<?= (int)$emp['id_usuario'] ?>">
                                <button type="submit" class="btn-sm btn-rojo">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<style>
    .empleados-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.2rem;
    }
    .btn-cancelar {
        background: #e5e7eb;
        color: #374151;
        border: none;
        padding: .6rem 1.2rem;
        border-radius: 7px;
        font-size: .9rem;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-cancelar:hover { background: #d1d5db; }
</style>

<script>
    function toggleFormEmpleado() {
        const w = document.getElementById('formEmpleadoWrapper');
        w.style.display = w.style.display === 'none' ? 'block' : 'none';
    }

    <?php if (!empty($mensajeEmpleado) && $tipoMensajeEmpleado === 'error'): ?>
    document.addEventListener('DOMContentLoaded', () => toggleFormEmpleado());
    <?php endif; ?>
</script>
