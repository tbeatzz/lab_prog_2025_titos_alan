// public/assets/js/producto/index.js
    import { productoController } from './controller.js';
    import { categoriaController } from '../categoria/controller.js';


document.addEventListener("DOMContentLoaded", async () => {
    await categoriaController.cargarOpcionesSelect('filterCategory');
    await productoController.list();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});


const configurarEventosTabla = () => {
    const tabla = document.querySelector('#productTable tbody');
    if (!tabla) {
        console.error('No se encontró el cuerpo de la tabla productTable');
        return;
    }

    tabla.addEventListener('click', async (e) => {
        const boton = e.target.closest('button[data-action]');
        if (!boton) return;

        const idProducto = parseInt(boton.dataset.productId);
        const action = boton.dataset.action;

        if (action === 'editar') {
            window.location.href = `producto/edit/${idProducto}`;
        } else if (action === 'eliminar' && confirm('¿Seguro que deseas eliminar este producto?')) {
            await productoController.delete(idProducto);
        }
    });
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('botonExportPdfItems');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            const categoria = document.getElementById('filterCategory').value;
            const nombre = document.getElementById('filterName').value;

            const filtros = {
                categoria: categoria || undefined,
                nombre: nombre || undefined
            };

            console.log('Exportando productos con filtros:', filtros);
            productoController.exportListPDF(filtros);
        });
    } else {
        console.error('Botón botonExportPdfItems no encontrado');
    }
};

const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonItemFiltros');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', async () => {
            const categoria = document.getElementById('filterCategory').value;
            const nombre = document.getElementById('filterName').value;
            const codigo = document.getElementById('filterCode').value;
            const orden = document.getElementById('filterOrden').value;

             console.log('Filtros aplicados:', { categoria, nombre,codigo,orden });

            await productoController.list({
                categoria: categoria || undefined,
                nombre: nombre || undefined,
                codigo: codigo || undefined,
                orden: orden || undefined
            });
        });
    } else {
        console.error('Botón botonItemFiltros no encontrado');
    }
};

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateItem');
    if (botonCreate) {
        botonCreate.addEventListener('click', () => {
            window.location.href = 'producto/create';
        });
    } else {
        console.error('Botón botonCreateItem no encontrado');
    }
};
