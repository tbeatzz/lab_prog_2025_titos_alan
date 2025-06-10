// public/app/js/user/index.js
import { userController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    cargarListaUsuarios();
    configurarEventosTabla();
    configurarBotonExportar();
    configurarBotonFiltros();
    configurarBotonAlta();
});

const cargarListaUsuarios = () => {
    userController.list();
};

const configurarEventosTabla = () => {
    const tabla = document.querySelector('#userTable tbody');
    if (!tabla) {
        console.error('No se encontró el cuerpo de la tabla userTable');
        return;
    }

    tabla.addEventListener('click', (e) => {
        const boton = e.target.closest('button[data-action]');
        if (boton) {
            const idUsuario = Number(boton.dataset.userId);
            const accion = boton.dataset.action;

            if (accion === 'editar') {
                window.location.href = `user/edit.html?id=${idUsuario}`;
            } else if (accion === 'eliminar' && confirm('¿Seguro que quieres eliminar este usuario?')) {
                userController.delete(idUsuario);
            }
        }
    });
};

const configurarBotonAlta = () => {
    const botonCreate = document.getElementById('botonCreateUser');
    if (botonCreate) {
        botonCreate.addEventListener('click', () => window.location.href = 'user/create.html');
    } else {
        console.error('Botón botonCreateUser no encontrado');
    }
};

const configurarBotonExportar = () => {
    const exportPdfButton = document.getElementById('export-pdf');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', () => {
            console.log('Exportando PDF');
            userController.exportToPDF();
        });
    } else {
        console.error('Botón export-pdf no encontrado');
    }
};

const configurarBotonFiltros = () => {
    const applyFiltersButton = document.getElementById('botonFiltros');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', () => {
            const perfil = document.getElementById('filterProfile').value;
            const email = document.getElementById('filterEmail').value;
            console.log('Filtros aplicados:', { perfil, email });
            userController.applyFilters(perfil, email);
            userController.list();
        });
    } else {
        console.error('Botón botonFiltros no encontrado');
    }
};
    // // Filtros dinámicos (actualizar al escribir en el campo de correo)
    // const filterEmail = document.getElementById('filterEmail');
    // if (filterEmail) {
    //     filterEmail.addEventListener('input', () => {
    //         const perfil = document.getElementById('filterProfile').value;
    //         const email = filterEmail.value;
    //         userController.applyFilters(perfil, email);
    //         userController.list();
    //     });
    // }

    // // Filtros dinámicos (actualizar al cambiar perfil)
    // const filterProfile = document.getElementById('filterProfile');
    // if (filterProfile) {
    //     filterProfile.addEventListener('change', () => {
    //         const perfil = filterProfile.value;
    //         const email = filterEmail.value;
    //         userController.applyFilters(perfil, email);
    //         userController.list();
    //     });
    // }
