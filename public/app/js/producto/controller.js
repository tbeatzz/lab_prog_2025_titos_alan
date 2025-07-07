import { productoService } from './service.js';

export const productoController = {
    async load(id, elements = null) {
        try {
            const response = await productoService.load(id);
            const producto = response.result;
            console.log('Producto cargado:', producto);

            if (elements) {
                sessionStorage.setItem('originalProductoData', JSON.stringify(producto));

                elements.id.value = producto.id || '';
                elements.nombre.value = producto.nombre || '';
                elements.codigo.value = producto.codigo || '';
                elements.categoria.value = producto.categoriaId || '';
                elements.precio.value = producto.precio ?? 0;
                elements.stock.value = producto.stock ?? 0;
                elements.descripcion.value = producto.descripcion || '';

                this.cancelEditMode(elements);
            } else {
                sessionStorage.setItem('editProductoId', id);
                window.location.href = `producto/edit/${id}`;
            }

            return producto;
        } catch (error) {
            console.error('Error al cargar producto:', error);
            Swal.fire('Error', error.message || 'Error al cargar producto', 'error');
            if (elements) window.location.href = 'producto/index';
            return null;
        }
    },

    async save() {
        try {
            const producto = {
                nombre: document.getElementById('nombre').value.trim(),
                codigo: document.getElementById('codigo').value.trim(),
                categoriaId: document.getElementById('categoria').value,
                precio: parseFloat(document.getElementById('precio').value),
                stock: parseInt(document.getElementById('stock').value, 10),
                descripcion: document.getElementById('descripcion').value.trim(),
            };

            await productoService.save(producto);
            return true;
        } catch (error) {
            console.error('Error al guardar producto', error);
            const message = error.response?.data?.message || error.message || 'Error al guardar producto';
            throw new Error(message);
        }
    },

    async update(elements) {
        try {
            const id = parseInt(elements.id.value);
            if (!id) throw new Error('ID inválido');

            const originalData = JSON.parse(sessionStorage.getItem('originalProductoData') || '{}');

            const producto = {
                id,
                nombre: elements.nombre.value.trim(),
                codigo: elements.codigo.value.trim(),
                categoriaId: elements.categoria.value,
                precio: parseFloat(elements.precio.value) || originalData.precio,
                stock: parseInt(elements.stock.value) || originalData.stock,
                descripcion: elements.descripcion.value.trim(),
                fechaCreacion: originalData.fechaCreacion,
            };

            await productoService.update(producto);
            sessionStorage.setItem('originalProductoData', JSON.stringify(producto));
            return true;
        } catch (error) {
            console.error('Error al actualizar producto', error);
            const message = error.response?.data?.message || error.message || 'Error al actualizar producto';
            throw new Error(message);
        }
    },


    // Eliminar producto
    async delete(id) {
        try {
            const result = await Swal.fire({
                title: '¿Eliminar producto?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                const response = await productoService.delete(id);
                // alert(response.message);
                await Swal.fire('Eliminado', 'Producto eliminado con éxito', 'success');
                // En vez de redirigir, actualizamos la lista
                window.location.href = 'producto/index';
                // await this.list(); // refresca la tabla con los productos actuales
            }
        } catch (error) {
            console.error('Error al eliminar producto:', error);
            Swal.fire('Error', error.message || 'Error al eliminar producto', 'error');
        }
    },



    async list(filters = {}) {
        try {
            const response = await productoService.list(filters);
            const productos = response.result;

            const tableBody = document.querySelector('#productTable tbody');
            if (!tableBody) throw new Error('Tabla productTable no encontrada');

            tableBody.innerHTML = '';

            if (!productos || productos.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">No hay registros disponibles.</td></tr>';
                return;
            }

            productos.forEach(producto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <th scope="row">${producto.id}</th>
                    <td>${producto.nombre}</td>
                    <td>${producto.codigo}</td>
                    <td>${producto.categoria || '-'}</td>
                    <td>$ ${producto.precio?.toFixed(2) ?? 0}</td>
                    <td>${producto.stock}</td>
                    <td>${producto.descripcion || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-product-id="${producto.id}" data-action="editar">
                            Editar <i class="bi bi-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-product-id="${producto.id}" data-action="eliminar">
                            Eliminar <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error al listar productos:', error);
            Swal.fire('Error', error.message || 'Error al listar productos', 'error');
        }
    },

    async exportListPDF(filters) {
        try {
            Swal.fire({
                title: 'Generando PDF...',
                text: 'Por favor espera unos segundos',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            await productoService.exportPdf(filters);
            Swal.close();
        } catch (error) {
            console.error('Error al exportar productos a PDF:', error);
            Swal.fire('Error', error.message || 'Error al exportar a PDF', 'error');
        }
    },

    async exportSinglePdf(id) {
        try {
            Swal.fire({
                title: 'Generando PDF...',
                text: 'Por favor espera unos segundos',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            await productoService.exportSinglePdf(id);
            Swal.close();
        } catch (error) {
            console.error('Error al exportar producto a PDF:', error);
            Swal.fire('Error', error.message || 'Error al exportar a PDF', 'error');
        }
    },

    enableEditMode(elements) {
        Object.values(elements).forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
                el.disabled = false;
            }
        });
        elements.updateButton.classList.remove('d-none');
        elements.cancelButton.classList.remove('d-none');
        elements.editButton.classList.add('d-none');
        elements.exportButton.classList.add('d-none');
        elements.deleteButton.classList.add('d-none');
    },

    cancelEditMode(elements) {
        const originalData = JSON.parse(sessionStorage.getItem('originalProductoData') || '{}');

        elements.nombre.value = originalData.nombre || '';
        elements.codigo.value = originalData.codigo || '';
        elements.categoria.value = originalData.categoriaId || '';
        elements.precio.value = originalData.precio ?? 0;
        elements.stock.value = originalData.stock ?? 0;
        elements.descripcion.value = originalData.descripcion || '';

        Object.values(elements).forEach(el => {
            if (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA') {
                el.disabled = true;
            }
        });
        elements.updateButton.classList.add('d-none');
        elements.cancelButton.classList.add('d-none');
        elements.editButton.classList.remove('d-none');
        elements.exportButton.classList.remove('d-none');
        elements.deleteButton.classList.remove('d-none');
    },

    resetForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            const idField = document.getElementById('id');
            if (idField) idField.value = '';
            const successMessage = document.getElementById('successMessage');
            if (successMessage) successMessage.classList.add('d-none');
        }
    },
};
