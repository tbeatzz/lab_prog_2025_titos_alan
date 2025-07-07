// public/js/producto/edit.js
import { productoController } from './controller.js';
import { categoriaController } from '../categoria/controller.js';

document.addEventListener('DOMContentLoaded', async () => {
    // Cargar opciones de categorías
    await categoriaController.cargarOpcionesSelect('categoria');

    const elements = {
        id: document.getElementById('id'),
        nombre: document.getElementById('nombre'),

        codigo: document.getElementById('codigo'),
        categoria: document.getElementById('categoria'),
        precio: document.getElementById('precio'),
        stock: document.getElementById('stock'),
        descripcion: document.getElementById('descripcion'),
        successMessage: document.getElementById('successMessage'),
        editButton: document.getElementById('editButton'),
        updateButton: document.getElementById('updateButton'),
        cancelButton: document.getElementById('cancelButton'),
        deleteButton: document.getElementById('deleteButton'),
        exportButton: document.getElementById('exportButton'),
        volverListadoButton: document.getElementById('volverListadoButton')
    };

    // Verificación de elementos
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Elemento con ID "${key}" no encontrado en el DOM`);
            alert(`Error: No se encontró el elemento "${key}" en la página`);
            window.location.href = 'producto/index';
            return;
        }
    }

    // Obtener el ID desde la URL
    function getItemIdFromUrl() {
        const urlParts = window.location.pathname.split('/');
        const id = parseInt(urlParts[urlParts.length - 1]);
        return isNaN(id) ? null : id;
    }

    const itemId = getItemIdFromUrl();
    console.log('ID obtenido desde URL:', itemId);

    if (!itemId) {
        console.error('ID de producto inválido');
        alert('ID de producto inválido');
        window.location.href = 'producto/index';
        return;
    }

    // Cargar producto
    document.body.style.cursor = 'wait';
    try {
        const item = await productoController.load(itemId, elements);
        if (!item) {
            alert('Producto no encontrado');
            window.location.href = 'producto/index';
            return;
        }

        // Mostrar la fecha de creación
        // elements.itemFecha.innerHTML = `<strong>Fecha de creación: </strong> ${item.fechaCreacion || 'N/A'}`;
    } catch (error) {
        console.error('Error al cargar datos del producto:', error);
        alert('Error al cargar los datos del producto');
        window.location.href = 'producto/index';
        return;
    } finally {
        document.body.style.cursor = 'default';
    }

    // Eventos
    elements.editButton.addEventListener('click', () => {
        productoController.enableEditMode(elements);
    });

    elements.updateButton.addEventListener('click', async () => {
        const errors = [];
        if (!elements.nombre.value.match(/^[A-Za-z\s]{2,50}$/)) {
            errors.push('El nombre debe contener solo letras y espacios, entre 2 y 50 caracteres');
        }
        if (!elements.codigo.value.match(/^[A-Za-z0-9]{3,10}$/)) {
            errors.push('El código debe contener solo letras y números, entre 3 y 10 caracteres');
        }
        if (!elements.categoria.value) {
            errors.push('La categoría es requerida');
        }

        if (!elements.precio.value || elements.precio.value <= 0 || elements.precio.value > 999999.99) {
            errors.push('El precio debe estar entre 0 y 999999.99');
        }
        if (!elements.stock.value || elements.stock.value < 0 || elements.stock.value > 10000) {
            errors.push('El stock debe estar entre 0 y 10000');
        }
        if (elements.descripcion.value.length > 500) {
            errors.push('La descripción no puede exceder los 500 caracteres');
        }

        if (errors.length > 0) {
            alert('Por favor corrige los siguientes errores:\n- ' + errors.join('\n- '));
            return;
        }

        elements.updateButton.disabled = true;
        elements.updateButton.innerHTML = 'Actualizando... <i class="bi bi-spinner"></i>';

        try {
            await productoController.update(elements);
            if (elements.successMessage) {
                elements.successMessage.classList.remove('d-none');
                setTimeout(() => {
                    window.location.href = 'producto/index';
                }, 2000);
            }
        } catch (error) {
            console.error('Error al actualizar el producto:', error);
            alert('Error al actualizar el producto: ' + (error.response?.data?.message || error.message || 'Error desconocido'));
        } finally {
            elements.updateButton.disabled = false;
            elements.updateButton.innerHTML = 'Actualizar <i class="bi bi-file-earmark-arrow-up"></i>';
        }
    });

    elements.cancelButton.addEventListener('click', () => {
        productoController.cancelEditMode(elements);
        const originalData = JSON.parse(sessionStorage.getItem('originalProductoData') || '{}');
        // elements.itemFecha.innerHTML = `<strong>Fecha de creación: </strong> ${originalData.fechaCreacion || 'N/A'}`;
    });

    elements.deleteButton.addEventListener('click', () => {
        productoController.delete(itemId);
    });

    elements.exportButton.addEventListener('click', () => {
        productoController.exportSinglePdf(itemId);
    });

    elements.volverListadoButton.addEventListener('click', () => {

        window.location.href = `producto/index`;
    })
});