const mensaje = document.getElementById('mensaje');
const contador = document.getElementById('char-count');

    mensaje.addEventListener('input', function() {

    const longitud = mensaje.value.length;
    contador.textContent = `${longitud} / 500 caracteres`;
    
    if (longitud >= 450) { //cambio de color cuando pase de 450
        contador.style.color = '#FF6B6B';
    } else {
        contador.style.color = '#718096';
    }
});