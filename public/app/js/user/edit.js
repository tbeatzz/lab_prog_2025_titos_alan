import { userController } from './controller.js';
import { userService } from './service.js';

document.addEventListener('DOMContentLoaded', () => {

    //preparar la vista, carga la info del usuario en el formulario usando el session storage
    const userId = sessionStorage.getItem('editUserId');
    let originalUserData = null;

    const userEstadoElement = document.getElementById('userEstado');
    const userFechaElement = document.getElementById('userFecha');

    if (userId) {
        const user = userService.load(parseInt(userId));
        if (user) {
            // Mostrar estado y fecha de creación
            userEstadoElement.innerHTML = `<strong>Estado de la cuenta:</strong> ${user.estado || 'N/A'}`;
            userFechaElement.innerHTML = `<strong>Fecha de creación:</strong> ${user.fechaCreacion || 'N/A'}`;

            document.getElementById('id').value = user.id || '';
            document.getElementById('apellidos').value = user.apellido || '';
            document.getElementById('nombres').value = user.nombres || '';
            document.getElementById('cuenta').value = user.cuenta || '';
            document.getElementById('perfil').value = user.perfil || '';
            document.getElementById('correo').value = user.correo || '';
            document.getElementById('clave').value = user.clave || '';
            document.getElementById('confirmarClave').value = user.clave || '';
            originalUserData = { ...user }; //para guardar la informacion
            // crea un nuevo objeto copiando todas las propiedades de user
            sessionStorage.removeItem('editUserId'); // limpia el session storage
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


    const updateButton = document.getElementById('updateButton');
    updateButton.addEventListener('click', () => {
        if (originalUserData) {
            userController.update(originalUserData.id);
            // Actualizar estado y fecha después de la actualización para no perderlos
            const updatedUser = userService.load(parseInt(userId));
            if (updatedUser) {
                userEstadoElement.innerHTML = `<strong>Estado de la cuenta:</strong> ${updatedUser.estado || 'N/A'}`;
                userFechaElement.innerHTML = `<strong>Fecha de creación:</strong> ${updatedUser.fechaCreacion || 'N/A'}`;
            }
        } else {
            alert('Error: No hay datos del usuario para actualizar');
        }
    });

    // boton de editar
    const editButton = document.getElementById('editButton');
    editButton.addEventListener('click', () => {
        userController.enableEditMode();
    });

    //boton de cancelar
    const cancelButton = document.getElementById("cancelButton");
    cancelButton.addEventListener("click", () => {
        userController.cancelEditMode();
        // Restore original data
        if (originalUserData) {
            document.getElementById("apellidos").value = originalUserData.apellido;
            document.getElementById("nombres").value = originalUserData.nombres;
            document.getElementById("cuenta").value = originalUserData.cuenta;
            document.getElementById("perfil").value = originalUserData.perfil;
            document.getElementById("correo").value = originalUserData.correo;
            document.getElementById("clave").value = originalUserData.clave;
            document.getElementById("confirmarClave").value = originalUserData.clave;

            // Restaurar estado y fecha mostrados
            userEstadoElement.innerHTML = `<strong>Estado de la cuenta:</strong> ${originalUserData.estado || 'N/A'}`;
            userFechaElement.innerHTML = `<strong>Fecha de creación:</strong> ${originalUserData.fechaCreacion || 'N/A'}`;
        }
    });

    //boton de exportaar a pdf
    const exportButton = document.getElementById('exportButton');
    exportButton.addEventListener('click', () => {
            const user = userService.load(parseInt(userId));
            if (user) {
                userController.exportSingleUserToPDF(user);
            } else {
                alert('Usuario no encontrado para exportar');
            }
        });

    //boton de borrar
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.addEventListener('click', () => {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            userController.delete(parseInt(userId));
            // console.log('lista usuarios', userService.list());
        }
    });
});