const updateButton = document.getElementById('updateButton');
const cancelButton = document.getElementById('cancelButton');
const exportButton = document.getElementById('exportButton');
const deleteButton = document.getElementById('deleteButton');

const editButton = document.getElementById('editButton')

const cambiarEdicion = (habilitar) => {
    const form = document.getElementById('editProductForm');
    const elementos = form.querySelectorAll('input, select, textarea');

    elementos.forEach(el => {
        el.disabled = !habilitar;
    });

    // con t saca la clase y con f agrega la clase
    updateButton.classList.toggle('d-none', !habilitar); 
    cancelButton.classList.toggle('d-none', !habilitar); 
    
    deleteButton.disabled = habilitar;
    exportButton.disabled = habilitar;
    editButton.disabled = habilitar;

    
};

const activarEdicion = () => cambiarEdicion(true);
const cancelarEdicion = () => cambiarEdicion(false); 
