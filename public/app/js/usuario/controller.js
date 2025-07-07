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
            Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
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

        if (!user.apellido || !user.nombres || !user.cuenta || !user.correo) {
            Swal.fire('Atención', 'Todos los campos son obligatorios', 'warning');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(user.correo)) {
            Swal.fire('Atención', 'Correo electrónico inválido', 'warning');
            return;
        }

        await usuarioService.save(user);

        await Swal.fire('Éxito', 'Usuario guardado correctamente', 'success');
        window.location.href = 'usuario/index';

    } catch (error) {
        console.error('Error al guardar usuario', error);

        const mensajeError = error?.response?.data?.message || error.message || 'Error al guardar usuario';
        Swal.fire('Error', mensajeError, 'error');
    }
},



    // Actualizar usuario
    update: async (elements) => {
        try {
            const clave = elements.clave.value;
            const confirmarClave = elements.confirmarClave.value;

            if (clave && clave !== confirmarClave) {
                Swal.fire('Error', 'Las contraseñas no coinciden', 'error');
                return;
            }

            const id = parseInt(elements.id.value);
            if (!id || isNaN(id)) {
                Swal.fire('Error', 'ID de usuario inválido', 'error');
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
                clave: clave || "",
                estado: originalUserData.estado ?? 1,
                fechaAlta: originalUserData.fechaAlta,
                resetPass: 0
            };

            if (!user.apellido || !user.nombres || !user.cuenta || !user.correo) {
                Swal.fire('Error', 'Todos los campos son obligatorios', 'warning');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(user.correo)) {
                Swal.fire('Error', 'Correo electrónico inválido', 'warning');
                return;
            }

            const response = await usuarioService.update(user);
            await Swal.fire('Actualizado', response.message || 'Usuario actualizado correctamente', 'success');
            window.location.href = `usuario/edit/${user.id}`;

        } catch (error) {
            console.error('Error al actualizar usuario:', error);
            Swal.fire('Error', error.message || 'Error inesperado al actualizar el usuario', 'error');
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
            const result = await Swal.fire({
                title: '¿Eliminar usuario?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                await usuarioService.delete(id);
                await Swal.fire('Eliminado', 'Usuario eliminado con éxito', 'success');
                window.location.href = 'usuario/index';
            }
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            Swal.fire('Error', error.message || 'Error al eliminar usuario', 'error');
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
            Swal.fire('Usuario habilitado', '', 'success').then(() => window.location.reload());
        } catch (error) {
            console.error('Error al habilitar usuario', error);
            Swal.fire('Error', error.message || 'Error al habilitar usuario', 'error');
        }
    },

    async disable(id) {
        try {
            await usuarioService.disable(id);
            Swal.fire('Usuario deshabilitado', '', 'success').then(() => window.location.reload());
        } catch (error) {
            console.error('Error al deshabilitar usuario', error);
            Swal.fire('Error', error.message || 'Error al deshabilitar usuario', 'error');
        }
    },

    async reset(id) {
        try {
            await usuarioService.reset(id);
            Swal.fire('Contraseña marcada para restablecimiento', '', 'success').then(() => window.location.reload());
        } catch (error) {
            console.error('Error al restablecer contraseña', error);
            Swal.fire('Error', error.message || 'Error al restablecer contraseña', 'error');
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
            Swal.fire('Error', error.message || 'Error al exportar a PDF', 'error');
        }
    },

    // Exportar datos de un usuario a PDF
    async exportSinglePdf(id) {
        try {
            await usuarioService.exportSinglePdf(id);
        } catch (error) {
            console.error('Error al exportar usuario a PDF:', error);
            Swal.fire('Error', error.message || 'Error al exportar a PDF', 'error');
        }
    },




};

