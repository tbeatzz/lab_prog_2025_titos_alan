import { itemController } from './controller.js';

document.addEventListener("DOMContentLoaded", async () => {
    await itemController.list();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#itemTable tbody');
    if (!tabla) return;

    tabla.addEventListener('click', async (e) => {
        const boton = e.target.closest('button[data-action]');
        if (!boton) return;

        const id = parseInt(boton.dataset.itemId);
        const action = boton.dataset.action;

        if (action === 'editar') {
            window.location.href = `edit.php/${id}`; // Asegurate de tener la página edit.html
        }

        if (action === 'eliminar') {
            await itemController.delete(id);
        }
    });
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('botonExportPdfItems');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            itemController.exportToPDF();
        });
    }
};

const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonItemFiltros');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', async () => {
            const categoria = document.getElementById('filterCategory').value;
            const nombre = document.getElementById('filterName').value;
            await itemController.applyFilters(categoria, nombre);
            await itemController.list();
        });
    }
};

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateItem');
    if (botonCreate) {
        botonCreate.addEventListener('click', () => {
            window.location.href = 'producto/create'; 
        });
    }
};
