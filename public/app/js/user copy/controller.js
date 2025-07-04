// public/app/js/user/controller.js
import { userService } from './service.js';

export const userController = {
    // load: (id) => {
    //     const user = userService.load(id);
    //     if (user) {
    //         sessionStorage.setItem('editUserId', id);
    //         window.location.href = 'user/edit.html';
    //     } else {
    //         alert('Usuario no encontrado');
    //     }
    // },

    save: () => {
        const clave = document.getElementById('clave').value;
        const confirmarClave = document.getElementById('confirmarClave').value;
        if (clave !== confirmarClave) {
            alert('Las contraseñas no coinciden');
            return;
        }

        const user = {
            id: 0,
            apellido: document.getElementById('apellidos').value,
            nombres: document.getElementById('nombres').value,
            cuenta: document.getElementById('cuenta').value,
            perfil: document.getElementById('perfil').value,
            correo: document.getElementById('correo').value,
            clave: clave,
            estado: 'Activa', // Valor por defecto
            fechaCreacion: new Date().toISOString().split('T')[0] // Fecha actual
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
            return savedUser;
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

        const originalUser = userService.load(id);
        if (!originalUser) {
            alert('Usuario no encontrado');
            return;
        }

        const user = {
            id: id,
            apellido: document.getElementById('apellidos').value,
            nombres: document.getElementById('nombres').value,
            cuenta: document.getElementById('cuenta').value,
            perfil: document.getElementById('perfil').value,
            correo: document.getElementById('correo').value,
            clave: clave || originalUser.clave, // Mantener clave si no se cambia
            estado: originalUser.estado,
            fechaCreacion: originalUser.fechaCreacion
        };

        const updatedUser = userService.update(user);
        if (updatedUser) {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                console.log('Usuario actualizado:', userService.load(id));
                successMessage.classList.remove('d-none');
            }
            setTimeout(() => {
                window.location.href = 'user/index.html';
            }, 2000);
            return updatedUser;
        } else {
            alert('Usuario no encontrado o error al actualizar');
        }
    },

    delete: (id) => {
        const deletedUser = userService.delete(id);
        if (deletedUser) {
            console.log('Usuarios restantes:', userService.list());
            alert('Usuario eliminado correctamente');
            window.location.href = 'user/index.html';
            return deletedUser;
        } else {
            alert('Usuario no encontrado');
        }
    },

    filteredUsers: null,

    applyFilters: (perfil, email) => {
        let users = userService.list();
        if (perfil) {
            users = users.filter(user => user.perfil.toLowerCase() === perfil.toLowerCase());
        }
        if (email) {
            const emailLower = email.toLowerCase();
            users = users.filter(user => user.correo.toLowerCase().includes(emailLower));
        }
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
                    <td>
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
        } else {
            console.error('Tabla userTable no encontrada');
        }
    },

    exportToPDF: () => {
        try {
            if (!window.jspdf || !window.jspdf.jsPDF) {
                console.error('jsPDF no está cargado');
                alert('Error: No se pudo cargar la librería jsPDF');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(16);
            doc.text('Lista de Usuarios - BajoCeroWear', 14, 20);

            const users = userController.filteredUsers || userService.list();
            if (!users || users.length === 0) {
                doc.setFontSize(12);
                doc.text('No hay usuarios para exportar', 14, 30);
                doc.save('usuarios.pdf');
                return;
            }

            const headers = ['ID', 'Usuario', 'Cuenta', 'Correo', 'Perfil', 'Estado', 'Fecha Creación'];
            const colWidths = [10, 30, 20, 45, 25, 20, 25];
            const pageWidth = 210;
            const margin = 14;
            const tableWidth = pageWidth - 2 * margin;

            const totalWidth = colWidths.reduce((a, b) => a + b, 0);
            if (totalWidth !== tableWidth) {
                console.warn(`El ancho total de las columnas (${totalWidth}) no coincide con el ancho de la tabla (${tableWidth}). Ajustando...`);
                const scaleFactor = tableWidth / totalWidth;
                for (let i = 0; i < colWidths.length; i++) {
                    colWidths[i] = colWidths[i] * scaleFactor;
                }
            }

            const truncateText = (text, maxWidth, fontSize) => {
                doc.setFontSize(fontSize);
                let width = doc.getTextWidth(text);
                if (width <= maxWidth) return text;
                let truncated = text;
                while (doc.getTextWidth(truncated + '...') > maxWidth && truncated.length > 0) {
                    truncated = truncated.slice(0, -1);
                }
                return truncated + '...';
            };

            let y = 30;
            doc.setFontSize(9);
            doc.setFillColor(0, 102, 204);
            doc.rect(14, y - 5, tableWidth, 8, 'F');
            doc.setTextColor(255, 255, 255);
            headers.forEach((header, i) => {
                let x = 14 + colWidths.slice(0, i).reduce((a, b) => a + b, 0);
                doc.text(header, x + 2, y);
            });
            doc.setTextColor(0, 0, 0);

            let x = 14;
            for (let i = 0; i <= headers.length; i++) {
                doc.line(x, y - 5, x, y + 3);
                if (i < headers.length) x += colWidths[i];
            }

            y += 8;
            users.forEach((user, rowIndex) => {
                if (y > 270) {
                    doc.addPage();
                    y = 20;
                    doc.setFontSize(7);
                    doc.setFillColor(0, 102, 204);
                    doc.rect(14, y - 5, tableWidth, 8, 'F');
                    doc.setTextColor(255, 255, 255);
                    headers.forEach((header, i) => {
                        let x = 14 + colWidths.slice(0, i).reduce((a, b) => a + b, 0);
                        doc.text(header, x + 2, y);
                    });
                    doc.setTextColor(0, 0, 0);
                    x = 14;
                    for (let i = 0; i <= headers.length; i++) {
                        doc.line(x, y - 5, x, y + 3);
                        if (i < headers.length) x += colWidths[i];
                    }
                    y += 8;
                }

                if (rowIndex % 2 === 0) {
                    doc.setFillColor(240, 240, 240);
                    doc.rect(14, y - 5, tableWidth, 8, 'F');
                }

                doc.setFontSize(7);
                x = 14;
                doc.text(user.id.toString(), x + 2, y);
                x += colWidths[0];
                doc.text(truncateText(`${user.apellido}, ${user.nombres}`, colWidths[1] - 4, 7), x + 2, y);
                x += colWidths[1];
                doc.text(truncateText(user.cuenta, colWidths[2] - 4, 7), x + 2, y);
                x += colWidths[2];
                doc.text(truncateText(user.correo, colWidths[3] - 4, 7), x + 2, y);
                x += colWidths[3];
                doc.text(truncateText(user.perfil, colWidths[4] - 4, 7), x + 2, y);
                x += colWidths[4];
                doc.text(truncateText(user.estado, colWidths[5] - 4, 7), x + 2, y);
                x += colWidths[5];
                doc.text(truncateText(user.fechaCreacion, colWidths[6] - 4, 7), x + 2, y);

                x = 14;
                for (let i = 0; i <= headers.length; i++) {
                    doc.line(x, y - 5, x, y + 3);
                    if (i < headers.length) x += colWidths[i];
                }

                y += 8;
            });

            doc.rect(14, 25, tableWidth, y - 25);
            doc.save('usuarios.pdf');
        } catch (error) {
            console.error('Error al generar PDF:', error);
            alert('Error al generar PDF: ' + error.message);
        }
    },

    exportSingleUserToPDF: (user) => {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(16);
            doc.text('Detalles de Usuario - BajoCeroWear', 10, 10);
            doc.setFontSize(12);
            doc.text(`ID: ${user.id}`, 10, 20);
            doc.text(`Usuario: ${user.apellido, user.nombres}`, 10, 30);
            doc.text(`Cuenta: ${user.cuenta}`, 10, 40);
            doc.text(`Perfil: ${user.perfil}`, 10, 50);
            doc.text(`Correo: ${user.correo}`, 10, 60);
            doc.text(`Estado: ${user.estado}`, 10, 70);
            doc.text(`Fecha de Creación: ${user.fechaCreacion}`, 10, 80);
            doc.save(`user_${user.id}.pdf`);
        } catch (error) {
            console.error('Error al generar PDF:', error);
            alert('Error al generar PDF: ' + error.message);
        }
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