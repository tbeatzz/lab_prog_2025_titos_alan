// public/app/js/user/edit.js
import { userController } from './controller.js';
import { userService } from './service.js';

document.addEventListener('DOMContentLoaded', () => {
    // Obtener el ID de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = parseInt(urlParams.get('id'));
    let originalUserData = null;

    // Verificar elementos del DOM
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
        exportButton: document.getElementById('exportButton')
    };

    // Verificar si todos los elementos existen
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Elemento con ID "${key}" no encontrado en el DOM`);
            alert(`Error: No se encontró el elemento "${key}" en la página`);
            window.location.href = 'user/index.html';
            return;
        }
    }

    if (userId) {
        const user = userService.load(userId);
        if (user) {
            // Mostrar estado y fecha de creación
            elements.userEstado.innerHTML = `<strong>Estado de la cuenta:</strong> ${user.estado || 'N/A'}`;
            elements.userFecha.innerHTML = `<strong>Fecha de creación:</strong> ${user.fechaCreacion || 'N/A'}`;

            // Llenar el formulario
            elements.id.value = user.id || '';
            elements.apellidos.value = user.apellido || '';
            elements.nombres.value = user.nombres || '';
            elements.cuenta.value = user.cuenta || '';
            elements.perfil.value = user.perfil || '';
            elements.correo.value = user.correo || '';
            elements.clave.value = ''; // No mostrar la contraseña por seguridad
            elements.confirmarClave.value = ''; // No mostrar la contraseña por seguridad
            originalUserData = { ...user }; // Guardar datos originales
        } else {
            alert('Usuario no encontrado');
            window.location.href = 'user/index.html';
            return;
        }
    } else {
        alert('No se proporcionó un ID de usuario');
        window.location.href = 'user/index.html';
        return;
    }

    // Botón de actualizar
    elements.updateButton.addEventListener('click', () => {
        if (originalUserData) {
            userController.update(originalUserData.id);
            // Actualizar estado y fecha mostrados después de la actualización
            const updatedUser = userService.load(userId);
            if (updatedUser) {
                elements.userEstado.innerHTML = `<strong>Estado de la cuenta:</strong> ${updatedUser.estado || 'N/A'}`;
                elements.userFecha.innerHTML = `<strong>Fecha de creación:</strong> ${updatedUser.fechaCreacion || 'N/A'}`;
            }
        } else {
            alert('Error: No hay datos del usuario para actualizar');
        }
    });

    // Botón de editar
    elements.editButton.addEventListener('click', () => {
        userController.enableEditMode();
    });

    // Botón de cancelar
    elements.cancelButton.addEventListener('click', () => {
        userController.cancelEditMode();
        if (originalUserData) {
            elements.apellidos.value = originalUserData.apellido;
            elements.nombres.value = originalUserData.nombres;
            elements.cuenta.value = originalUserData.cuenta;
            elements.perfil.value = originalUserData.perfil;
            elements.correo.value = originalUserData.correo;
            elements.clave.value = ''; // No restaurar la contraseña
            elements.confirmarClave.value = ''; // No restaurar la contraseña
            elements.userEstado.innerHTML = `<strong>Estado de la cuenta:</strong> ${originalUserData.estado || 'N/A'}`;
            elements.userFecha.innerHTML = `<strong>Fecha de creación:</strong> ${originalUserData.fechaCreacion || 'N/A'}`;
        }
    });

    // Botón de exportar a PDF
    elements.exportButton.addEventListener('click', () => {
        const user = userService.load(userId);
        if (user) {
            userController.exportSingleUserToPDF(user);
        } else {
            alert('Usuario no encontrado para exportar');
        }
    });

    // Botón de eliminar
    elements.deleteButton.addEventListener('click', () => {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            userController.delete(userId);
        }
    });
});