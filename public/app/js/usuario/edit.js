// public/app/js/usuario/edit.js
import { usuarioController } from './controller.js';

document.addEventListener('DOMContentLoaded', async () => {
    const elements = {
        userEstado: document.getElementById('userEstado'),
        userFecha: document.getElementById('userFecha'),
        id: document.getElementById('id'),
        apellidos: document.getElementById('apellidos'),
        nombres: document.getElementById('nombres'),
        cuenta: document.getElementById('cuenta'),
        perfil: document.getElementById('perfil'),
        correo: document.getElementById('correo'),
        clave: document.getElementById('clave'),
        confirmarClave: document.getElementById('confirmarClave'),
        successMessage: document.getElementById('successMessage'),
        editButton: document.getElementById('editButton'),
        updateButton: document.getElementById('updateButton'),
        cancelButton: document.getElementById('cancelButton'),
        deleteButton: document.getElementById('deleteButton'),
        exportButton: document.getElementById('exportButton'),
        volverListadoButton: document.getElementById('volverListadoButton')
    };

    // Verificación básica de elementos
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Elemento con ID "${key}" no encontrado en el DOM`);
            alert(`Error: No se encontró el elemento "${key}" en la página`);
            window.location.href = 'usuario/index';
            return;
        }
    }

    // Obtener el ID desde la URL
    function getUserIdFromUrl() {
        const urlParts = window.location.pathname.split('/');
        const id = parseInt(urlParts[urlParts.length - 1]);
        return isNaN(id) ? null : id;
    }

    const userId = getUserIdFromUrl();
    console.log('ID obtenido desde URL:', userId);

    if (!userId) {
        console.error('ID de usuario inválido');
        alert('ID de usuario inválido');
        window.location.href = 'usuario/index';
        return;
    }

    // Cargar usuario
    document.body.style.cursor = 'wait';
    try {
        const user = await usuarioController.load(userId, elements);
        if (!user) {
            alert('Usuario no encontrado');
            window.location.href = 'usuario/index';
            return;
        }

        // Colocamos el ID en el campo oculto
        elements.id.value = user.id;
    } catch (error) {
        console.error('Error al cargar datos del usuario:', error);
        alert('Error al cargar los datos del usuario');
        window.location.href = 'usuario/index';
        return;
    } finally {
        document.body.style.cursor = 'default';
    }

    // Eventos
    elements.editButton.addEventListener('click', () => {
        usuarioController.enableEditMode(elements);
    });

    elements.updateButton.addEventListener('click', () => {
        usuarioController.update(elements);
    });

    elements.cancelButton.addEventListener('click', () => {
        usuarioController.cancelEditMode(elements);
    });

    elements.deleteButton.addEventListener('click', () => {
        usuarioController.delete(userId);
    });

    elements.exportButton.addEventListener('click', () => {
        usuarioController.exportSinglePdf(userId);
    });

     elements.volverListadoButton.addEventListener('click', () => {

        window.location.href = `usuario/index`;
    })


});
