import { itemController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    // Obtener el formulario y los campos
    const form = document.getElementById('createItemForm');
    const elements = {
        nombre: document.getElementById('nombre'),
        codigo: document.getElementById('codigo'),
        categoria: document.getElementById('categoria'),
        precio: document.getElementById('precio'),
        stock: document.getElementById('stock'),
        descripcion: document.getElementById('descripcion'),
        successMessage: document.getElementById('successMessage')
    };

    // Verificar si el formulario y los campos existen
    if (!form) {
        console.error('Formulario con ID "createItemForm" no encontrado en el DOM');
        alert('Error: No se encontró el formulario de creación');
        window.location.href = 'items/index.html';
        return;
    }

    for (const [key, element] of Object.entries(elements)) {
        if (!element && key !== 'successMessage') { // successMessage puede no existir inicialmente
            console.error(`Elemento con ID "${key}" no encontrado en el DOM`);
            alert(`Error: No se encontró el elemento "${key}" en el formulario`);
            window.location.href = 'items/index.html';
            return;
        }
    }

    // Resetear el formulario
    itemController.resetForm('createItemForm');

    // Manejar el envío del formulario
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        // Validar campos requeridos
        const errors = [];
        if (!elements.nombre.value.trim()) {
            errors.push('El nombre es requerido');
        }
        if (!elements.codigo.value.trim()) {
            errors.push('El código es requerido');
        }
        if (!elements.categoria.value.trim()) {
            errors.push('La categoría es requerida');
        }
        if (!elements.precio.value || elements.precio.value <= 0) {
            errors.push('El precio debe ser mayor que 0');
        }
        if (!elements.stock.value || elements.stock.value < 0) {
            errors.push('El stock no puede ser negativo');
        }

        // Mostrar errores si los hay
        if (errors.length > 0) {
            alert('Por favor corrige los siguientes errores:\n- ' + errors.join('\n- '));
            return;
        }

        // Llamar a save si las validaciones pasan
        try {
            const savedItem = itemController.save();
            if (savedItem && elements.successMessage) {
                elements.successMessage.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Error al guardar el item:', error);
            alert('Error al guardar el item: ' + error.message);
        }
    });
});