import { categoriaController } from './controller.js';

document.addEventListener('DOMContentLoaded', async () => {
    const elements = {
        id: document.getElementById('id'),
        nombre: document.getElementById('categoria'),
        successMessage: document.getElementById('successMessage'),
        editButton: document.getElementById('editButton'),
        updateButton: document.getElementById('updateButton'),
        cancelButton: document.getElementById('cancelButton'),
        deleteButton: document.getElementById('deleteButton'),
        exportButton: document.getElementById('exportButton')
    };

    // Validación de elementos
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Elemento con ID "${key}" no encontrado`);
            alert(`Error: No se encontró el elemento "${key}" en la página`);
            window.location.href = 'categoria/index';
            return;
        }
    }

    // Obtener ID de URL
    const getCatIdFromUrl = () => {
        const parts = window.location.pathname.split('/');
        const id = parseInt(parts.at(-1));
        return isNaN(id) ? null : id;
    };

    const catId = getCatIdFromUrl();
    if (!catId) {
        alert('ID de categoría inválido');
        window.location.href = 'categoria/index';
        return;
    }

    // Cargar datos
    document.body.style.cursor = 'wait';
    try {
        const categoria = await categoriaController.load(catId, elements);
        if (!categoria) throw new Error('No se encontró la categoría');
        elements.id.value = categoria.id;
    } catch (err) {
        alert(err.message);
        window.location.href = 'categoria/index';
    } finally {
        document.body.style.cursor = 'default';
    }

    // Eventos
    elements.editButton.addEventListener('click', () => categoriaController.enableEditMode(elements));
    elements.updateButton.addEventListener('click', () => categoriaController.update(elements));
    elements.cancelButton.addEventListener('click', () => categoriaController.cancelEditMode(elements));
    elements.deleteButton.addEventListener('click', () => categoriaController.delete(catId));
    elements.exportButton.addEventListener('click', () => categoriaController.exportSinglePdf(catId));
});