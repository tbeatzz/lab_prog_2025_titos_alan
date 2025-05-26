// public/app/js/user/controller.js
import { userService } from './service.js';


export const userController = {
    load: (id) => {
        const user = userService.load(id);
        if (user) {
            sessionStorage.setItem('editUserId', id);
            window.location.href = 'user/edit.html';
        } else {
            alert('Usuario no encontrado');
        }
    },

    // public/app/js/user/controller.js (relevant method)
    save: () => {
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
            clave: clave
        };


        const savedUser = userService.save(user);
        if (savedUser) {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.classList.remove('d-none');
            }
            setTimeout(() => {
                window.location.href = 'user/index.html';
            }, 2000);
        } else {
            alert('Error al guardar el usuario');
        }
    },

    update: (id) => {
        const clave = document.getElementById('clave').value;
        const confirmarClave = document.getElementById('confirmarClave').value;
        if (clave !== confirmarClave) {
            alert('Las contraseñas no coinciden');
            return;
        }
        // console.log('updateando');
        const user = {
            id: id,
            apellido: document.getElementById('apellidos').value,
            nombres: document.getElementById('nombres').value,
            cuenta: document.getElementById('cuenta').value,
            perfil: document.getElementById('perfil').value,
            correo: document.getElementById('correo').value,
            clave: clave
        };
        // console.log(user);

        const updatedUser = userService.update(user);
        if (updatedUser) {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                console.log('usuario final', userService.load(id));
                successMessage.classList.remove('d-none');
            }
            // setTimeout(() => {
            //     window.location.href = 'user/index.html';
            // }, 2000);
        } else {
            alert('Usuario no encontrado o error al actualizar');
        }
    },

    delete: (id) => {
        const deletedUser = userService.delete(id);
        if (deletedUser) {
            alert('Usuario eliminado correctamente');
            // window.location.href = 'user/index.html';
        
        } else {
            alert('Usuario no encontrado');
        }
    },

     // Variable para almacenar usuarios filtrados
    filteredUsers: null,

    // Método para aplicar filtros
    applyFilters: (perfil, email) => {
        let users = userService.list();

        // Filtrar por perfil
        if (perfil) {
            users = users.filter(user => user.perfil.toLowerCase() === perfil.toLowerCase());
        }

        // Filtrar por correo
        if (email) {
            const emailLower = email.toLowerCase();
            users = users.filter(user => user.correo.toLowerCase().includes(emailLower));
        }

        // Almacenar usuarios filtrados
        userController.filteredUsers = users;
        return users;
    },


    list: (filters = {}) => {
        const users = userController.filteredUsers || userService.list();
        const tableBody = document.querySelector('#userTable tbody');
        if (tableBody) {
            tableBody.innerHTML = '';
            if (users.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No hay registros disponibles.</td></tr>';
                return;
            }
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${user.id}</th>
                    <td>${user.apellido}, ${user.nombres}</td>
                    <td>${user.cuenta}</td>
                    <td>${user.perfil}</td>
                    <td>${user.correo}</td>
                    <td >
                        <button class="btn btn-sm btn-outline-primary" data-user-id="${user.id}" data-action="editar">
                        Editar <i class="bi bi-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-user-id="${user.id}" data-action="eliminar">
                            Eliminar <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } 
    },

   exportToPDF: () => {
    try {
        console.log('Verificando jsPDF:', window.jspdf);
        if (!window.jspdf || !window.jspdf.jsPDF) {
            console.error('jsPDF no está cargado');
            alert('Error: No se pudo cargar la librería jsPDF');
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Título
        doc.setFontSize(16);
        doc.text('Lista de Usuarios - BajoCeroWear', 14, 20);

        // Obtener usuarios
        const users = userController.filteredUsers || userService.list();
        console.log('Usuarios para exportar:', users);
        if (!users || users.length === 0) {
            doc.setFontSize(12);
            doc.text('No hay usuarios para exportar', 14, 30);
            doc.save('usuarios.pdf');
            return;
        }

        // Configuración de tabla
        const headers = ['ID', 'Usuario', 'Cuenta', 'Correo', 'Perfil', 'Estado', 'Fecha Creación'];
        const colWidths = [10, 30, 20, 45, 25, 20, 25];
        let y = 30;

        // Dibujar cabecera
        doc.setFontSize(9);
        doc.setFillColor(0, 102, 204);
        doc.rect(14, y - 5, 182, 8, 'F');
        doc.setTextColor(255, 255, 255);
        headers.forEach((header, i) => {
            let x = 14 + colWidths.slice(0, i).reduce((a, b) => a + b, 0);
            doc.text(header, x + 2, y);
        });
        doc.setTextColor(0, 0, 0);

        // Dibujar datos
        y += 8;
        users.forEach((user, rowIndex) => {
            if (y > 270) {
                doc.addPage();
                y = 20;
            }

            if (rowIndex % 2 === 0) {
                doc.setFillColor(240, 240, 240);
                doc.rect(14, y - 5, 182, 8, 'F');
            }

            let x = 14;
            doc.text(user.id.toString(), x + 2, y);
            x += colWidths[0];
            doc.text(`${user.apellido}, ${user.nombres}`, x + 2, y);
            x += colWidths[1];
            doc.text(user.cuenta, x + 2, y);
            x += colWidths[2];
            doc.text(user.correo, x + 2, y);
            x += colWidths[3];
            doc.text(user.perfil, x + 2, y);
            x += colWidths[4];
            doc.text(user.estado, x + 2, y);
            x += colWidths[5];
            doc.text(user.fechaCreacion, x + 2, y);

            y += 8;
        });

        doc.rect(14, 25, 182, y - 25);
        doc.save('usuarios.pdf');
    } catch (error) {
        console.error('Error al generar PDF:', error);
        alert('Error al generar el PDF: ' + error.message);
    }
},

    exportSingleUserToPDF: (user) => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text('Detalles de Usuario - BajoCeroWear', 10, 10);
        doc.text(`ID: ${user.id}`, 10, 20);
        doc.text(`Usuario: ${user.apellido}, ${user.nombres}`, 10, 30);
        doc.text(`Cuenta: ${user.cuenta}`, 10, 40);
        doc.text(`Perfil: ${user.perfil}`, 10, 50);
        doc.text(`Correo: ${user.correo}`, 10, 60);
        doc.text(`Estado: ${user.estado}`, 10, 70);
        doc.text(`Fecha de Creación: ${user.fechaCreacion}`, 10, 80);
        doc.save(`user_${user.id}.pdf`);
    },

    enableEditMode: () => {
        const form = document.getElementById('editUserForm');
        if (form) {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => input.disabled = false);
            document.getElementById('editButton').classList.add('d-none');
            document.getElementById('updateButton').classList.remove('d-none');
            document.getElementById('cancelButton').classList.remove('d-none');

            document.getElementById('deleteButton').disabled = true;
            document.getElementById('exportButton').disabled = true;
        }
    },

    cancelEditMode: () => {
        const form = document.getElementById('editUserForm');
        if (form) {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => input.disabled = true);
            document.getElementById('editButton').classList.remove('d-none');
            document.getElementById('updateButton').classList.add('d-none');
            document.getElementById('cancelButton').classList.add('d-none');

            document.getElementById('deleteButton').disabled = false;
            document.getElementById('exportButton').disabled = false;
        }
    },

    resetForm: (formId) => {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            const idField = document.getElementById('id');
            if (idField) idField.value = '';
        }
    }
};