const cambiarEdicion = (habilitar) => {
    const form = document.getElementById('editUserForm');
    const elementos = form.querySelectorAll('input, select');

    elementos.forEach(el => {
        el.disabled = !habilitar;
    });

    const updateButton = document.getElementById('updateButton');
    const cancelButton = document.getElementById('cancelButton');

    // con t saca la clase y con f agrega la clase
    updateButton.classList.toggle('d-none', !habilitar); 
    cancelButton.classList.toggle('d-none', !habilitar); 
};

const activarEdicion = () => cambiarEdicion(true);
const cancelarEdicion = () => cambiarEdicion(false); 
