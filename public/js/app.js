document.addEventListener('DOMContentLoaded', function() {

    // Para confirmar antes de eliminar una cuenta
    const input = document.getElementById('confirmacion');
    const boton = document.getElementById('btnEliminar');
    const error = document.getElementById('errorConfirmacion');

    if (input && boton && error) {

        input.addEventListener('input', function() {

            if (input.value === 'ELIMINAR') {
                boton.disabled = false;
                error.style.display = 'none';
            } else {
                boton.disabled = true;
                error.style.display = 'block';
            }
        });
    }

    // Para mostrar los modales de Aviso legal y Política de privacidad
    const modalPrivacidad = document.getElementById('modalPrivacidad');
    const modalAviso = document.getElementById('modalAviso');
    const modalReservas = document.getElementById('modalReservas');
    const modalReservasUser = document.getElementById('modalReservasUser');

    const linkPrivacidad = document.getElementById('linkPrivacidad');
    const linkAviso = document.getElementById('linkAviso');
    const linkReservas = document.getElementById('linkReservas');
    const linkReservasUser = document.getElementById('linkReservasUser');

    const cerrarBtns = document.querySelectorAll('.cerrar');

    if(linkPrivacidad){
        linkPrivacidad.addEventListener('click', function(e) {
            e.preventDefault();
            modalPrivacidad.style.display = 'block';
        });
    }

    if(linkAviso){
        linkAviso.addEventListener('click', function(e) {
            e.preventDefault();
            modalAviso.style.display = 'block';
        });
    }

    if(linkReservas){
        linkReservas.addEventListener('click', function(e) {
            e.preventDefault();
            modalReservas.style.display = 'block';
        });
    }

    if(linkReservasUser){
        linkReservasUser.addEventListener('click', function(e) {
            e.preventDefault();
            modalReservasUser.style.display = 'block';
        });
    }

    cerrarBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            btn.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });

});





