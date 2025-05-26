import { itemController } from './controller.js';
// Carga la lista de usuarios al iniciar la página
document.addEventListener('DOMContentLoaded', () => {
    cargarListaItems();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});


const cargarListaItems = () => {
    itemController.list();
}

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#itemTable tbody');
    tabla.addEventListener('click', (e) => {
        const boton = e.target.closest('button[data-action]');
        // recoger datos del data
        const idItem = Number(boton.dataset.itemId);
        const accion = boton.dataset.action;
        console.log(accion, idItem)

        if (accion === 'editar') {
            itemController.load(idItem);
        } else if (accion === 'eliminar' && confirm('¿Seguro que quieres eliminar este usuario?')) {
            itemController.delete(idItem);
        }
    });

}
const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('botonExportPdfItems');
        if (exportPdfButton) {
            exportPdfButton.addEventListener('click', () => {
                console.log('Exportando PDF'); // debug
                itemController.exportToPDF();
            });
        } 
}
const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonItemFiltros');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', () => {
            const categoria = document.getElementById('filterCategory').value;
            const nombre = document.getElementById('filterName').value;
            console.log('Filtros aplicados:', { categoria, nombre });
            itemController.applyFilters(categoria, nombre);
            itemController.list();
        });
    } else {
        console.error('Botón botonFiltros no encontrado');
    }
}

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateItem');
    if (botonCreate) {
        botonCreate.addEventListener('click', () =>  window.location.href= 'items/create.html');
    }
}