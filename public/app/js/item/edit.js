// const updateButton = document.getElementById('updateButton');
// const cancelButton = document.getElementById('cancelButton');
// const exportButton = document.getElementById('exportButton');
// const deleteButton = document.getElementById('deleteButton');

// const editButton = document.getElementById('editButton')

// const cambiarEdicion = (habilitar) => {
//     const form = document.getElementById('editProductForm');
//     const elementos = form.querySelectorAll('input, select, textarea');

//     elementos.forEach(el => {
//         el.disabled = !habilitar;
//     });

//     // con t saca la clase y con f agrega la clase
//     updateButton.classList.toggle('d-none', !habilitar); 
//     cancelButton.classList.toggle('d-none', !habilitar); 
    
//     deleteButton.disabled = habilitar;
//     exportButton.disabled = habilitar;
//     editButton.disabled = habilitar;

    
// };

// const activarEdicion = () => cambiarEdicion(true);
// const cancelarEdicion = () => cambiarEdicion(false); 

import { itemController } from './controller.js';
import { itemService } from './service.js';

document.addEventListener('DOMContentLoaded', () => {

    //preparar la vista, carga la info del usuario en el formulario usando el session storage
    const itemId = sessionStorage.getItem('editItemId');
    let originalItemData = null;

    if (itemId) {
        const item = itemService.load(parseInt(itemId));
        if (item) {

            document.getElementById('id').value = item.id || '';
            document.getElementById('nombre').value = item.apellido || '';
            document.getElementById('codigo').value = item.nombres || '';
            document.getElementById('categoria').value = item.cuenta || '';
            document.getElementById('precio').value = item.perfil || '';
            document.getElementById('stock').value = item.correo || '';
            document.getElementById('descripcion').value = item.clave || '';
            originalItemData = { ...item }; //para guardar la informacion
            // crea un nuevo objeto copiando todas las propiedades de user
            sessionStorage.removeItem('editItemId'); // limpia el session storage
        } else {
            alert('Usuario no encontrado');
            window.location.href = 'item/index.html';
            return;
        }
    } else {
        alert('No se proporcionó un ID de un item');
        window.location.href = 'items/index.html';
        return;
    }


    const updateButton = document.getElementById('updateButton');
    updateButton.addEventListener('click', () => {
        if (originalItemData) itemController.update(originalItemData.id);
        else alert('Error: No hay datos del usuario para actualizar');
    });

    // boton de editar
    const editButton = document.getElementById('editButton');
    editButton.addEventListener('click', () => {
        itemController.enableEditMode();
    });

    //boton de cancelar
    const cancelButton = document.getElementById("cancelButton");
    cancelButton.addEventListener("click", () => {
        itemController.cancelEditMode();
        //restaurar si sale del modo edicion 
        if (originalItemData) {
            document.getElementById("nombre").value = originalItemData.apellido;
            document.getElementById("codigo").value = originalItemData.nombres;
            document.getElementById("categoria").value = originalItemData.cuenta;
            document.getElementById("stock").value = originalItemData.perfil;
            document.getElementById("precio").value = originalItemData.correo;
            document.getElementById("descripcion").value = originalItemData.clave;
        }
    });

    //boton de exportaar a pdf
    const exportButton = document.getElementById('exportButton');
    exportButton.addEventListener('click', () => {
            const item = itemService.load(parseInt(itemId));
            if (item) {
                itemController.exportSingleItemToPDF(item);
            } else {
                alert('Usuario no encontrado para exportar');
            }
        });

    //boton de borrar
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.addEventListener('click', () => {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            itemController.delete(parseInt(itemId));
            // console.log('lista usuarios', userService.list());
        }
    });
});