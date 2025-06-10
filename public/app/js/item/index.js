import { itemController } from './controller.js';

// Carga la lista de ítems al iniciar la página
document.addEventListener('DOMContentLoaded', () => {
    cargarListaItems();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});

const cargarListaItems = () => {
    itemController.list();
};

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#itemTable tbody');
    if (tabla) {
        tabla.addEventListener('click', (e) => {
            const boton = e.target.closest('button[data-action]');
            if (boton) {
                const idItem = Number(boton.dataset.itemId);
                const accion = boton.dataset.action;
                console.log(accion, idItem); // Debug

                if (accion === 'editar') {
                    window.location.href = `items/edit.html?id=${idItem}`;
                } else if (accion === 'eliminar' && confirm('¿Seguro que quieres eliminar este ítem?')) {
                    itemController.delete(idItem);
                }
            }
        });
    } else {
        console.error('Tabla itemTable no encontrada');
    }
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('botonExportPdfItems');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            console.log('Exportando PDF'); // Debug
            itemController.exportToPDF();
        });
    } else {
        console.error('Botón botonExportPdfItems no encontrado');
    }
};

const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonItemFiltros');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', () => {
            const categoria = document.getElementById('filterCategory').value;
            const nombre = document.getElementById('filterName').value;
            console.log('Filtros aplicados:', { categoria, nombre }); // Debug
            itemController.applyFilters(categoria, nombre);
            itemController.list();
        });
    } else {
        console.error('Botón botonItemFiltros no encontrado');
    }
};

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateItem');
    if (botonCreate) {
        botonCreate.addEventListener('click', () => window.location.href = 'items/create.html');
    } else {
        console.error('Botón botonCreateItem no encontrado');
    }
};