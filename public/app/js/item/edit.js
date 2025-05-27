// public/app/js/items/edit.js
import { itemController } from './controller.js';
import { itemService } from './service.js';

document.addEventListener('DOMContentLoaded', () => {
    // Preparar la vista, carga la info del item en el formulario usando sessionStorage
    const itemId = sessionStorage.getItem('editItemId');
    let originalItemData = null;

    const fechaCreacionElement = document.getElementById('itemFecha');

    if (itemId) {
        const item = itemService.load(parseInt(itemId));
        if (item) {
            // Mostrar la fecha de creación
            fechaCreacionElement.innerHTML = `<strong>Fecha de creación: </strong> ${item.fechaCreacion || 'N/A'}`;

            document.getElementById('id').value = item.id || '';
            document.getElementById('nombre').value = item.nombre || '';
            document.getElementById('estado').value = item.estado || '';
            document.getElementById('codigo').value = item.codigo || '';
            document.getElementById('categoria').value = item.categoria || '';
            document.getElementById('precio').value = item.precio || '';
            document.getElementById('stock').value = item.stock || '';
            document.getElementById('descripcion').value = item.descripcion || '';
            originalItemData = { ...item }; // Guardar la información original
            sessionStorage.removeItem('editItemId'); // Limpiar sessionStorage
        } else {
            alert('Item no encontrado');
            window.location.href = 'items/index.html';
            return;
        }
    } else {
        alert('No se proporcionó un ID de un item');
        window.location.href = 'items/index.html';
        return;
    }

    const updateButton = document.getElementById('updateButton');
    updateButton.addEventListener('click', () => {
        if (originalItemData) {
            itemController.update(originalItemData.id);
            // Actualizar la fecha mostrada después de la actualización
            const updatedItem = itemService.load(parseInt(itemId));
            if (updatedItem) {
                fechaCreacionElement.innerHTML = `<strong>Fecha de creación: </strong> ${updatedItem.fechaCreacion || 'N/A'}`;
            }
        } else {
            alert('Error: No hay datos del item para actualizar');
        }
    });

    // Botón de editar
    const editButton = document.getElementById('editButton');
    editButton.addEventListener('click', () => {
        itemController.enableEditMode();
    });

    // Botón de cancelar
    const cancelButton = document.getElementById("cancelButton");
    cancelButton.addEventListener("click", () => {
        itemController.cancelEditMode();
        // Restaurar si sale del modo edición
        if (originalItemData) {
            document.getElementById("nombre").value = originalItemData.nombre;
            document.getElementById("estado").value = originalItemData.estado;
            document.getElementById("codigo").value = originalItemData.codigo;
            document.getElementById("categoria").value = originalItemData.categoria;
            document.getElementById("precio").value = originalItemData.precio;
            document.getElementById("stock").value = originalItemData.stock;
            document.getElementById("descripcion").value = originalItemData.descripcion;
            // Restaurar la fecha mostrada
            fechaCreacionElement.innerHTML = `<strong>Fecha de creación: </strong> ${originalItemData.fechaCreacion || 'N/A'}`;
        }
    });

    // Botón de exportar a PDF
    const exportButton = document.getElementById('exportButton');
    exportButton.addEventListener('click', () => {
        const item = itemService.load(parseInt(itemId));
        if (item) {
            itemController.exportSingleItemToPDF(item);
        } else {
            alert('Item no encontrado para exportar');
        }
    });

    // Botón de borrar
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.addEventListener('click', () => {
        if (confirm('¿Estás seguro de que deseas eliminar este item?')) {
            itemController.delete(parseInt(itemId));
        }
    });
});