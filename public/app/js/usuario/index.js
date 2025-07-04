// public/app/js/usuario/index.js
import { usuarioController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    cargarListaUsuarios();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});

const cargarListaUsuarios = () => {
    usuarioController.list();
};

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#userTable tbody');
    if (!tabla) {
        console.error('No se encontró el cuerpo de la tabla userTable');
        return;
    }

    tabla.addEventListener('click', (e) => {
        const boton = e.target.closest('button[data-action]');
        if (!boton) return;

        const idUsuario = Number(boton.dataset.userId);
        const accion = boton.dataset.action;

        if (accion === 'editar') {
            // Redirigir a la URL amigable
            window.location.href = `usuario/edit/${idUsuario}`;
        } else if (accion === 'eliminar' && confirm('¿Seguro que quieres eliminar este usuario?')) {
            usuarioController.delete(idUsuario);
        }
    });
};

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateUser');
    if (botonCreate) {
        botonCreate.addEventListener('click', () => window.location.href = 'usuario/create');
    } else {
        console.error('Botón botonCreateUser no encontrado');
    }
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('export-pdf');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            console.log('Exportando PDF');
            // usuarioController.exportToPDF();  <- desactivado por ahora
        });
    } else {
        console.error('Botón export-pdf no encontrado');
    }
};

const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonFiltros');
    if (!applyFiltersButton) {
        console.error('Botón botonFiltros no encontrado');
        return;
    }

    applyFiltersButton.addEventListener('click', async () => {
        const perfil = document.getElementById('filterProfile').value;
        const email = document.getElementById('filterEmail').value;

        console.log('Filtros aplicados:', { perfil, email });

        await usuarioController.list({
            perfil: perfil || undefined,
            correo: email || undefined
        });
    });
};

//  filtros dinámicos:
// const filterEmail = document.getElementById('filterEmail');
// filterEmail?.addEventListener('input', async () => {
//     await usuarioController.list({ perfil: document.getElementById('filterProfile').value, correo: filterEmail.value });
// });

// const filterProfile = document.getElementById('filterProfile');
// filterProfile?.addEventListener('change', async () => {
//     await usuarioController.list({ perfil: filterProfile.value, correo: document.getElementById('filterEmail').value });
// });
