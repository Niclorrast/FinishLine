
    function mostrarTab(e, tab) {
            e.preventDefault();

            document.querySelectorAll('.tab-content')
            .forEach(t => t.classList.remove('activo'));

            document.querySelectorAll('.sidebar-link')
            .forEach(l => l.classList.remove('activo'));

            document.getElementById('tab-' + tab)
            ?.classList.add('activo');

            e.target.classList.add('activo');
        }

    /*
     * Modo oscuro (DARK MODE)
     */

    const btnDark = document.getElementById('toggleDark');

    // Cargar preferencia guardada
    if (localStorage.getItem('darkmode') === 'activo') {
    document.body.classList.add('dark-mode');
}

    btnDark.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');

    // Guardar preferencia
    if (document.body.classList.contains('dark-mode')) {
    localStorage.setItem('darkmode', 'activo');
} else {
    localStorage.setItem('darkmode', 'desactivado');
}

btnDark.textContent = document.body.classList.contains('dark-mode')
    ? '☀️ Modo claro'
    : '🌙 Modo nocturno';
});
