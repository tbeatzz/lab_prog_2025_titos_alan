// public/assets/js/usuario/index.js
import { categoriaController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    cargarListaUsuarios();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});

const cargarListaUsuarios = () => {
    categoriaController.list();
};

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#catTable tbody');
    if (!tabla) {
        console.error('No se encontró el cuerpo de la tabla ');
        return;
    }

    tabla.addEventListener('click', (e) => {
        const boton = e.target.closest('button[data-action]');
        if (!boton) return;

        const idCategoria = Number(boton.dataset.catId);
        const accion = boton.dataset.action;

        if (accion === 'editar') {
            // Redirigir a la URL amigable
            window.location.href = `categoria/edit/${idCategoria}`;
        } else if (accion === 'eliminar') {
            categoriaController.delete(idCategoria);
        }
    });
};

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateCat');
    if (botonCreate) {
        botonCreate.addEventListener('click', () => window.location.href = 'categoria/create');
    } else {
        console.error('Botón botonCreateUser no encontrado');
    }
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('exportPdfButton');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            // Leer filtros de los inputs
            const nombre = document.getElementById('filterName').value;
    
            const filtros = {
                nombre: nombre || undefined,
          
            };

            console.log('Exportando PDF con filtros:', filtros);
            categoriaController.exportListPDF(filtros);
        });
    } else {
        console.error('Botón exportPdfButton no encontrado');
    }
};
const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonCatFiltros');
    if (!applyFiltersButton) {
        console.error('Botón botonCatFiltros no encontrado');
        return;
    }

    applyFiltersButton.addEventListener('click', async () => {
        const nombre = document.getElementById('filterName').value;
        
        console.log('Filtros aplicados:', { nombre});

        await categoriaController.list({
            nombre: nombre || undefined
        });
    });
};

//  filtros dinámicos:
// const filterEmail = document.getElementById('filterEmail');
// filterEmail?.addEventListener('input', async () => {
//     await categoriaController.list({ perfil: document.getElementById('filterProfile').value, correo: filterEmail.value });
// });

// const filterProfile = document.getElementById('filterProfile');
// filterProfile?.addEventListener('change', async () => {
//     await categoriaController.list({ perfil: filterProfile.value, correo: document.getElementById('filterEmail').value });
// });
