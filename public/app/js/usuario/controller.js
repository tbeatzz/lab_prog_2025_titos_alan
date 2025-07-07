// public/app/js/usuario/controller.js
import { usuarioService } from './service.js';

export const usuarioController = {
    // Cargar y mostrar datos del usuario
    async load(id, elements = null) {
        try {
            const response = await usuarioService.load(id);
            const user = response.result; // Compatible con UsuarioService::load
            console.log('Usuario cargado:', user); // Para depuración
            if (elements) {
                // Guardar datos originales en sessionStorage
                sessionStorage.setItem('originalUserData', JSON.stringify(user));
                
                // Mostrar estado y fecha
                elements.userEstado.innerHTML = `<strong>Estado de la cuenta:</strong> ${user.estado ? 'Activo' : 'Inactivo'}`;
                elements.userFecha.innerHTML = `<strong>Fecha de creación:</strong> ${user.fechaAlta || 'N/A'}`;

                // Llenar el formulario
                elements.id.value = user.id || '';
                elements.apellidos.value = user.apellido || '';
                elements.nombres.value = user.nombres || '';
                elements.cuenta.value = user.cuenta || '';
                elements.perfil.value = user.perfil || '';
                elements.correo.value = user.correo || '';
                elements.clave.value = '';
                elements.confirmarClave.value = '';

                // Deshabilitar inputs inicialmente
                this.cancelEditMode(elements);
            } else {
                sessionStorage.setItem('editUserId', id);
                window.location.href = `usuario/edit/${id}`;
            }
            
            return user;
        } catch (error) {
            console.error('Error al cargar usuario:', error);
            alert(error.message || 'Error al cargar usuario');
            if (elements) {
                window.location.href = 'usuario/index';
            }
            return null;
        }
    },

    // Guardar un nuevo usuario
    async save() {
        try {
            const clave = document.getElementById('clave').value;
            const confirmarClave = document.getElementById('confirmarClave').value;

            if (clave !== confirmarClave) {
                alert('Las contraseñas no coinciden');
                return;
            }

            const user = {
                apellido: document.getElementById('apellidos').value,
                nombres: document.getElementById('nombres').value,
                cuenta: document.getElementById('cuenta').value,
                perfil: document.getElementById('perfil').value,
                correo: document.getElementById('correo').value,
                clave: clave,
                estado: 1,
                fechaAlta: new Date().toISOString().split('T')[0],
                resetPass: 0
            };

            // Validaciones
            if (!user.apellido || !user.nombres || !user.cuenta || !user.correo) {
                alert('Todos los campos son obligatorios');
                return;
            }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(user.correo)) {
                alert('Correo electrónico inválido');
                return;
            }

            await usuarioService.save(user);
            alert('Usuario guardado correctamente');
            window.location.href = 'usuario/index';
        } catch (error) {
            console.error('Error al guardar usuario', error);
            alert(error.message || 'Error al guardar usuario');
        }
    },

    // Actualizar usuario
    update: async (elements) => {
    try {
        const clave = elements.clave.value;
        const confirmarClave = elements.confirmarClave.value;

        if (clave && clave !== confirmarClave) {
            alert('Las contraseñas no coinciden');
            return;
        }

        const id = parseInt(elements.id.value);
        if (!id || isNaN(id)) {
            alert('ID de usuario inválido');
            return;
        }

        const originalUserData = JSON.parse(sessionStorage.getItem('originalUserData') || '{}');

        const user = {
            id,
            apellido: elements.apellidos.value.trim(),
            nombres: elements.nombres.value.trim(),
            cuenta: elements.cuenta.value.trim(),
            perfil: elements.perfil.value,
            correo: elements.correo.value.trim(),
            clave: clave || "", // Asegura que clave sea una cadena vacía si no se proporciona
            estado: originalUserData.estado ?? 1,
            fechaAlta: originalUserData.fechaAlta,
            resetPass: 0
        };

        // Validaciones
        if (!user.apellido || !user.nombres || !user.cuenta || !user.correo) {
            alert('Todos los campos son obligatorios');
            return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(user.correo)) {
            alert('Correo electrónico inválido');
            return;
        }

        const response = await usuarioService.update(user);
        alert(response.message || 'Usuario actualizado correctamente');
        window.location.href = `usuario/edit/${user.id}`;
    } catch (error) {
        console.error('Error al actualizar usuario:', error);
        alert(error.message || 'Error inesperado al actualizar el usuario');
    }
},
    // Cancelar edición
    cancelEditMode(elements) {
        const originalUserData = JSON.parse(sessionStorage.getItem('originalUserData') || '{}');
        if (originalUserData) {
            elements.apellidos.value = originalUserData.apellido || '';
            elements.nombres.value = originalUserData.nombres || '';
            elements.cuenta.value = originalUserData.cuenta || '';
            elements.perfil.value = originalUserData.perfil || '';
            elements.correo.value = originalUserData.correo || '';
            elements.clave.value = '';
            elements.confirmarClave.value = '';

            elements.exportButton.classList.remove('d-none');   
            elements.deleteButton.classList.remove('d-none');   
        }

        // Deshabilitar inputs
        Object.values(elements).forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
                el.disabled = true;
            }
        });

        // Actualizar visibilidad de botones
        elements.editButton?.classList.remove('d-none');
        elements.updateButton?.classList.add('d-none');
        elements.cancelButton?.classList.add('d-none');
    },

    // Habilitar edición
    enableEditMode(elements) {
        Object.values(elements).forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
                el.disabled = false;
            }
        });

        elements.editButton?.classList.add('d-none');
        elements.updateButton?.classList.remove('d-none');
        elements.cancelButton?.classList.remove('d-none');

        elements.exportButton.classList.toggle('d-none');   
        elements.deleteButton.classList.toggle('d-none');   
    },

    // Eliminar usuario
    async delete(id) {
        try {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                await usuarioService.delete(id);
                alert('Usuario eliminado con éxito');
                window.location.href = 'usuario/index';
            }
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            alert(error.message || 'Error al eliminar usuario');
        }
    },

   


    // Listar usuarios
    async list(filters = {}) {
        try {
            const response = await usuarioService.list(filters);
            const users = response.result;

            const tableBody = document.querySelector('#userTable tbody');
            if (!tableBody) {
                throw new Error('Tabla userTable no encontrada');
            }

            tableBody.innerHTML = '';

            if (!users || !users.length) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No hay registros disponibles.</td></tr>';
                return;
            }

            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${user.id}</th>
                    <td>${user.apellido || ''}, ${user.nombres || ''}</td>
                    <td>${user.cuenta || ''}</td>
                    <td>${user.perfil || ''}</td>
                    <td>${user.correo || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-action="editar" data-user-id="${user.id}">
                            Editar <i class="bi bi-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-action="eliminar" data-user-id="${user.id}">
                            Eliminar <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error al listar usuarios:', error);
            const tableBody = document.querySelector('#userTable tbody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Error al cargar los usuarios.</td></tr>';
            }
            alert(error.message || 'Error al listar usuarios');
        }
    },

    // Habilitar usuario
    async enable(id) {
        try {
            await usuarioService.enable(id);
            alert('Usuario habilitado correctamente');
            window.location.reload();
        } catch (error) {
            console.error('Error al habilitar usuario', error);
            alert(error.message || 'Error al habilitar usuario');
        }
    },

    // Deshabilitar usuario
    async disable(id) {
        try {
            await usuarioService.disable(id);
            alert('Usuario deshabilitado correctamente');
            window.location.reload();
        } catch (error) {
            console.error('Error al deshabilitar usuario', error);
            alert(error.message || 'Error al deshabilitar usuario');
        }
    },

    // Restablecer contraseña
    async reset(id) {
        try {
            await usuarioService.reset(id);
            alert('Contraseña marcada para restablecimiento');
            window.location.reload();
        } catch (error) {
            console.error('Error al restablecer contraseña', error);
            alert(error.message || 'Error al restablecer contraseña');
        }
    },

    // Reiniciar formulario
    resetForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            const idField = document.getElementById('id');
            if (idField) idField.value = '';
        }
    },

    // Exportar lista de usuarios a PDF
   async exportListPDF(filters) {
        try {
            await usuarioService.exportPdf(filters);
        } catch (error) {
            console.error('Error al exportar lista de usuarios a PDF:', error);
            alert(error.message || 'Error al exportar a PDF');
        }
    },

    // Exportar datos de un usuario a PDF
    async exportSinglePdf(id) {
        try {
            await usuarioService.exportSinglePdf(id);
        } catch (error) {
            console.error('Error al exportar usuario a PDF:', error);
            alert(error.message || 'Error al exportar a PDF');
        }
    },

  
};