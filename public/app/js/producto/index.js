import { productoController } from './controller.js';
import { categoriaController } from '../categoria/controller.js';

document.addEventListener("DOMContentLoaded", async () => {
    try {
        console.log('⏳ Cargando categorías y lista de productos...');
        await categoriaController.cargarOpcionesSelect('filterCategory');
        await productoController.list();
        configurarEventosTabla();
        configurarBotonExportar();
        configurarBotonFiltros();
        configurarBotonAlta();
        console.log('✅ Página cargada correctamente');
    } catch (error) {
        console.error('Error al iniciar la página:', error);
        alert('Ocurrió un error al cargar la página. Ver consola para más detalles.');
    }
});

const obtenerFiltros = () => ({
    categoria: document.getElementById('filterCategory')?.value || undefined,
    nombre: document.getElementById('filterName')?.value || undefined,
    codigo: document.getElementById('filterCode')?.value || undefined,
    orden: document.getElementById('filterOrden')?.value || undefined
});

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#productTable tbody');
    if (!tabla) {
        console.error('No se encontró el cuerpo de la tabla productTable');
        alert('Tabla de productos no disponible.');
        return;
    }

    tabla.addEventListener('click', async (e) => {
        const boton = e.target.closest('button[data-action]');
        if (!boton) return;

        const idProducto = parseInt(boton.dataset.productId);
        const action = boton.dataset.action;

        if (!idProducto || isNaN(idProducto)) {
            console.warn('ID de producto inválido:', boton.dataset.productId);
            alert('ID de producto no válido.');
            return;
        }

        if (action === 'editar') {
            window.location.href = `producto/edit/${idProducto}`;
        } else if (action === 'eliminar') {
            await productoController.delete(idProducto);
        }
    });
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('botonExportPdfItems');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            const filtros = obtenerFiltros();
            console.log(' Exportando productos con filtros:', filtros);
            productoController.exportListPDF(filtros);
        });
    } else {
        console.error(' Botón botonExportPdfItems no encontrado');
        alert('No se puede exportar. Botón de exportación no disponible.');
    }
};

const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonItemFiltros');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', async () => {
            const filtros = obtenerFiltros();
            console.log('🔍 Filtros aplicados:', filtros);
            await productoController.list(filtros);
        });
    } else {
        console.error(' Botón botonItemFiltros no encontrado');
        alert('No se pueden aplicar filtros. Botón no disponible.');
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
        alert('No se puede crear un nuevo producto. Botón no disponible.');
    }
};
