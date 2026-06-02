function moverCarrusel(btnODireccion, direccion) {
    let wrapper, dir;

    if (typeof btnODireccion === 'number') {
        // Llamada desde el carrusel de comentarios: moverCarrusel(1)
        wrapper = document.getElementById('carruselComentarios');
        dir = btnODireccion;
        var claseActivo = 'active';
    } else {
        // Llamada desde servicios: moverCarrusel(this, 1)
        wrapper = btnODireccion.closest('.carrusel-wrapper');
        dir = direccion;
        var claseActivo = 'activo';
    }

    const slides = wrapper.querySelectorAll('.carousel-item, .carrusel-slide');
    const dots   = wrapper.querySelectorAll('.dot');

    let actual = Array.from(slides).findIndex(s => s.classList.contains(claseActivo));

    slides[actual].classList.remove(claseActivo);
    if (dots[actual]) dots[actual].classList.remove(claseActivo);

    actual = (actual + dir + slides.length) % slides.length;

    slides[actual].classList.add(claseActivo);
    if (dots[actual]) dots[actual].classList.add(claseActivo);
}

function irASlide(indice) {
    const wrapper = document.getElementById('carruselComentarios');
    const slides  = wrapper.querySelectorAll('.carousel-item');
    const dots    = wrapper.querySelectorAll('.dot');

    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d =>   d.classList.remove('active'));

    slides[indice].classList.add('active');
    dots[indice].classList.add('active');
}

// Autoplay solo para el carrusel de comentarios
setInterval(() => {
    if (document.getElementById('carruselComentarios')) {
        moverCarrusel(1);
    }
}, 5000);