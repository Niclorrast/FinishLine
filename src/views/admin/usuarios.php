<?php
/**
 * Variables inyectadas desde panel.php
 * @var array $stats
 * @var array $tickets
 * @var array $colores_estado
 * @var array $usuarios
 */ ?>

<section class="tab-content" id="tab-usuarios">
    <h1 class="admin-titulo">Gestión de Usuarios</h1>
    <input type="text" id="buscadorUsuarios" placeholder="🔍 Buscar por nombre, correo o rol..." class="buscador-input"
    oninput="filtrarPorUsuarios()">
    <select id="filtroRol" class="select-rol" onchange="filtrarPorUsuarios()">
        <option value="">Todos los roles</option>
        <option value="admin">Admin</option>
        <option value="empleado">Empleado</option>
        <option value="cliente">Cliente</option>
    </select>

    <div class="tabla-wrapper">
        <table class="admin-tabla" id="tablaUsuarios">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Rol</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($u['Nombre'] . ' ' . $u['Apellido']) ?></td>
                    <td><?= htmlspecialchars($u['Correo']) ?></td>
                    <td><?= htmlspecialchars($u['Telefono'] ?? '—') ?></td>
                    <td>
                        <span class="badge-rol" style="background:<?= $colores_rol[$u['Rol']] ?? '#999' ?>">
                            <?= htmlspecialchars($u['Rol']) ?>
                        </span>
                    </td>
                    <td class="acciones-celda">
                        <?php if (esAdmin()): ?>
                        <form method="POST" class="form-inline">
                            <input type="hidden" name="accion"     value="cambiar_rol">
                            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                            <select name="rol" class="select-rol">
                                <option value="cliente"  <?= $u['Rol']==='cliente'  ? 'selected':'' ?>>cliente</option>
                                <option value="empleado" <?= $u['Rol']==='empleado' ? 'selected':'' ?>>empleado</option>
                                <option value="admin"    <?= $u['Rol']==='admin'    ? 'selected':'' ?>>admin</option>
                            </select>
                            <button type="submit" class="btn-sm btn-azul">Guardar</button>
                        </form>
                        <?php endif; ?>



                        <?php if (esAdmin() && $u['id_usuario'] !== (int)$_SESSION['usuario']['id']): ?>
                        <form method="POST" class="form-inline"
                            onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars($u['Nombre']) ?>? Esta acción no se puede deshacer.')">
                            <input type="hidden" name="accion"     value="eliminar_usuario">
                            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                            <button type="submit" class="btn-sm btn-rojo">Eliminar</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function filtrarPorUsuarios() {
            const q   = document.getElementById('buscadorUsuarios').value.toLowerCase();
            const rol = document.getElementById('filtroRol').value.toLowerCase();

            document.querySelectorAll('#tablaUsuarios tbody tr').forEach(fila => {
                const nombre = fila.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
                const correo = fila.querySelector('td:nth-child(3)')?.textContent.toLowerCase() ?? '';
                const celdaRol = fila.querySelector('td:nth-child(5)')?.textContent.toLowerCase().trim() ?? '';

                const coincideTexto = nombre.includes(q) || correo.includes(q);
                const coincideRol   = rol === '' || celdaRol.includes(rol);

                fila.style.display = (coincideTexto && coincideRol) ? '' : 'none';
            });
        }

    </script>
</section>