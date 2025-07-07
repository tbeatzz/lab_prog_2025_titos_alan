import { categoriaService } from './service.js';

export const categoriaController = {
 // Cargar y mostrar datos del usuario
    async load(id, elements) {
        try {
            const { result: cat } = await categoriaService.load(id);
            sessionStorage.setItem('originalUserData', JSON.stringify(cat));


            if (elements) {
                elements.id.value = cat.id;
                elements.nombre.value = cat.nombre;
                this.cancelEditMode(elements);
            }

            return cat;
        } catch (err) {
            console.error('Error al cargar categoría:', err);
            throw err;
        }
    }, 

    async cargarOpcionesSelect(selectId) {
        try {
            const response = await categoriaService.list();
            const categorias = response.result;


            const select = document.getElementById(selectId);

            if (!select) {
                console.error(`No se encontró el select con ID: ${selectId}`);
                return;
            }

            // Limpiar opciones existentes
            select.innerHTML = '<option value="">Todas las categorías</option>';

            // Agregar categorías dinámicamente
            categorias.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id; // o cat.id, según lo que uses como filtro
                option.textContent = cat.nombre;
                select.appendChild(option);
            });
        } catch (error) {
            console.error("Error al cargar categorías en el select:", error);
        }
    },
    // Listar categorias
    async list(filters = {}) {
        try {
            const response = await categoriaService.list(filters);
            const categorias = response.result;

            const tableBody = document.querySelector('#catTable tbody');
            if (!tableBody) {
                throw new Error('Tabla catTable no encontrada');
            }

            tableBody.innerHTML = '';

            if (!categorias || !categorias.length) {
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No hay registros disponibles.</td></tr>';
                return;
            }

            categorias.forEach(cat => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${cat.id}</th>
                    <td>${cat.nombre || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-action="editar" data-cat-id="${cat.id}">
                            Editar <i class="bi bi-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-action="eliminar" data-cat-id="${cat.id}">
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

    // Guardar una nueva categoria
    async save() {
        try {

            const categoria = {
                nombre: document.getElementById('categoria').value,

            };

            // Validaciones
            if (!categoria.nombre) {
                alert('Todos los campos son obligatorios');
                return;
            }

            await categoriaService.save(categoria);
            alert('Cateogria guardada correctamente');
            window.location.href = 'categoria/index';
        } catch (error) {
            console.error('Error al guardar categoria', error);
            alert(error.message || 'Error al guardar categoria');
        }
    },

    // Eliminar usuario
    // Eliminar categoria
    async delete(id) {
        try {
            if (confirm('¿Estás seguro de que deseas eliminar esta categoría?')) {
                const response = await categoriaService.delete(id);

                if (response.status === false) {
                    alert(response.message || 'No se pudo eliminar la categoría');
                    return;
                }

                alert('Categoría eliminada con éxito');
                window.location.href = 'categoria/index';
            }
        } catch (error) {
            console.error('Error al eliminar categoría:', error);
            alert(error.message || 'Error inesperado al eliminar categoría');
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

    // Actualizar usuario
    update: async (elements) => {
        try {
            const id = parseInt(elements.id.value);
            if (!id || isNaN(id)) {
                alert('ID de categoría inválido');
                return;
            }

            const cat = {
                id,
                nombre: elements.nombre.value.trim()
            };

            // Validación
            if (!cat.nombre) {
                alert('El nombre de la categoría es obligatorio');
                return;
            }

            const response = await categoriaService.update(cat); // Cambia aquí también al servicio correcto
            alert(response.message || 'Categoría actualizada correctamente');
            sessionStorage.removeItem('originalUserData');
            window.location.href = `categoria/index`;
        } catch (error) {
            console.error('Error al actualizar la categoría:', error);
            alert(error.message || 'Error inesperado al actualizar la categoría');
        }
    },

    // Cancelar edición
    cancelEditMode(elements) {
        const originalCatData = JSON.parse(sessionStorage.getItem('originalUserData') || '{}');
        if (originalCatData) {
            elements.nombre.value = originalCatData.nombre || '';
        }

        Object.values(elements).forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
                el.disabled = true;
            }
        });

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
    },

    // Exportar lista de usuarios a PDF
    async exportListPDF(filters) {
        try {
            await categoriaService.exportPdf(filters);
        } catch (error) {
            console.error('Error al exportar lista de usuarios a PDF:', error);
            alert(error.message || 'Error al exportar a PDF');
        }
    },

    // Exportar datos de un usuario a PDF
    async exportSinglePdf(id) {
        try {
            await categoriaService.exportSinglePdf(id);
        } catch (error) {
            console.error('Error al exportar usuario a PDF:', error);
            alert(error.message || 'Error al exportar a PDF');
        }
    },


};
