import { itemService } from './service.js';

export const itemController = {
    load: (id) => {
        // console.log('quiero editar');   debug
        const item = itemService.load(id);
        if (item){
            sessionStorage.setItem('editItemId', id);
            
            window.location.href = 'items/edit.html';
            console.log('se recibio el id', id);
        }else{
            alert('Usuario no encontrado')
        }
    },
    save: () => {
        const item = {
            nombre: document.getElementById('nombre').value,
            codigo: document.getElementById('codigo').value,
            categoria: document.getElementById('categoria').value,
            precio: document.getElementById('precio').value,
            stock: document.getElementById('stock').value,
            descripcion: document.getElementById('descripcion').value,
        };


        const savedItem = itemService.save(item);
        if (savedItem) {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.classList.remove('d-none');
            }
            setTimeout(() => {
                window.location.href = 'items/index.html';
            }, 2000);
        } else {
            alert('Error al guardar el usuario');
        }         
    },
    update: () => {

    },
    delete: (id) => {

    },
    // Variable para almacenar usuarios filtrados
    filteredItems: null,
    applyFilters: (categoria, nombre) => {

        let items = itemService.list();

        if(categoria){
            items = items.filter(item => item.categoria.toLowerCase() === categoria.toLowerCase());
        }
        if(nombre){
            items = items.filter(item => item.nombre.toLowerCase() === categoria.toLowerCase());
        }

        itemController.filteredItems = items;
        return items;

    },
    list:(filters = {}) =>{
        const items = itemController.filteredItems || itemService.list();
        const tableBody = document.querySelector('#itemTable tbody');
        
        if (tableBody){
            tableBody.innerHTML = '';
            if(items.length === 0){
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No hay registros disponibles.</td></tr>';
                return;
            }
            items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <th scope="row">${item.id}</th>
                <td>${item.nombre}</td>
                <td>${item.codigo}</td>
                <td>${item.categoria}</td>
                <td>$ ${item.precio}</td>
                <td>${item.stock}</td>
                <td>${item.descripcion}</td>
                <td >
                    <button class="btn btn-sm btn-outline-primary" data-item-id="${item.id}" data-action="editar">
                        Editar <i class="bi bi-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" data-item-id="${item.id}" data-action="eliminar">
                        Eliminar <i class="bi bi-trash3"></i>
                    </button>
            </td>
            `;
            tableBody.appendChild(row);
            });
        }
    },
    exportToPDF: () => {

    },
    exportSingleItemToPDF: (item) => {

    },
    enableEditMode: () => {
        const form = document.getElementById('editItemForm');
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
        const form = document.getElementById('editItemForm');
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
    resertForm: (formId) => {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            const idField = document.getElementById('id');
            if (idField) idField.value = '';
        }
    },

}