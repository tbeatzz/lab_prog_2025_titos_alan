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
            id: 0,
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
                console.log(itemService.list()); //debug para ver si se agrega el item
                return savedItem;
            }
            // setTimeout(() => {
            //     window.location.href = 'items/index.html';
            // }, 2000);
        } else {
            alert('Error al guardar el usuario');
        }         
    },
    update: (id) => {
        // Obtener el item original para conservar fecha
        const originalItem = itemService.load(id);
        if (!originalItem) {
            alert('Producto no encontrado');
            return;
        }

        const item = {
            id: id,
            nombre: document.getElementById('nombre').value,
            estado:document.getElementById('estado').value,
            codigo: document.getElementById('codigo').value,
            categoria: document.getElementById('categoria').value,
            precio: parseFloat(document.getElementById('precio').value),
            stock: parseInt(document.getElementById('stock').value),
            descripcion: document.getElementById('descripcion').value,
            fechaCreacion: originalItem.fechaCreacion, // Conservar fechaCreacion
        };

        const updatedItem = itemService.update(item);
        if (updatedItem) {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.classList.remove('d-none');
                console.log(itemService.list()); //debug para ver si se actualiza el item
                return updatedItem;
            }
            // setTimeout(() => {
            //     window.location.href = 'items/index.html';
            // }, 2000);
        } else {
            alert('Producto no encontrado o error al actualizar');
        }
    },
    delete: (id) => {
        const deletedItem = itemService.delete(id);
        if (deletedItem) {
            console.log(itemService.list()); //debug para ver si se elimina el item
            alert('Producto eliminado correctamente, revisar consola para ver si se elimino el item');
            // window.location.href = 'items/index.html'; // Redirigir después de eliminar
            return deletedItem;
        } else {
            alert('Producto no encontrado');
        }
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
                <td>${item.estado}</td>
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
        try {
            if (!window.jspdf || !window.jspdf.jsPDF) {
                console.error('jsPDF no está cargado');
                alert('Error: No se pudo cargar la librería jsPDF');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text('Lista de Productos - BajoCeroWear', 14, 20);

            const items = itemController.filteredItems || itemService.list();
            if (!items || items.length === 0) {
                doc.setFontSize(12);
                doc.text('No hay productos para exportar', 14, 30);
                doc.save('productos.pdf');
                return;
            }

            const headers = ['ID', 'Nombre', 'Código', 'Categoría', 'Precio', 'Stock', 'Descripción', 'Estado', 'Fecha'];
            const colWidths = [10, 25, 20, 20, 15, 15, 30, 15, 15];
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
            items.forEach((item, rowIndex) => {
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
                doc.text(item.id.toString(), x + 2, y);
                x += colWidths[0];
                doc.text(truncateText(item.nombre, colWidths[1] - 4, 8), x + 2, y);
                x += colWidths[1];
                doc.text(truncateText(item.codigo, colWidths[2] - 4, 8), x + 2, y);
                x += colWidths[2];
                doc.text(truncateText(item.categoria, colWidths[3] - 4, 8), x + 2, y);
                x += colWidths[3];
                doc.text(`$${item.precio.toFixed(2)}`, x + 2, y);
                x += colWidths[4];
                doc.text(item.stock.toString(), x + 2, y);
                x += colWidths[5];
                doc.text(truncateText(item.descripcion || 'Sin descripción', colWidths[6] - 4, 8), x + 2, y);
                x += colWidths[6];
                doc.text(truncateText(item.estado, colWidths[7] - 4, 8), x + 2, y);
                x += colWidths[7];
                doc.text(truncateText(item.fechaCreacion, colWidths[8] - 4, 8), x + 2, y);

                x = 14;
                for (let i = 0; i <= headers.length; i++) {
                    doc.line(x, y - 5, x, y + 3);
                    if (i < headers.length) x += colWidths[i];
                }

                y += 8;
            });

            doc.rect(14, 25, tableWidth, y - 25);
            doc.save('productos.pdf');
        } catch (error) {
            console.error('Error al generar PDF:', error);
            alert('Error al generar el PDF: ' + error.message);
        }
    },
    exportSingleItemToPDF: (item) => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text('Detalles de producto - BajoCeroWear', 10, 10);
        doc.text(`ID: ${item.id}`, 10, 20);
        doc.text(`Nombre: ${item.nombre}`, 10, 30);
        doc.text(`Codigo: ${item.codigo}`, 10, 40);
        doc.text(`Categoria: ${item.categoria}`, 10, 50);
        doc.text(`Precio: ${item.precio}`, 10, 60);
        doc.text(`Stock: ${item.stock}`, 10, 70);
        doc.text(`Descripcion: ${item.descripcion || 'Sin descripción'}`, 10, 80)
        doc.text(`Estado: ${item.estado }`, 10,90);
        doc.text(`Fecha de creacion: ${item.fechaCreacion}`, 10, 100    );;
        doc.save(`item_${item.id}.pdf`);
    },
    enableEditMode: () => {
        const form = document.getElementById('editItemForm');
        if (form) {
            const inputs = form.querySelectorAll('input, select, textarea');
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
            const inputs = form.querySelectorAll('input, select, textarea');
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
    },

}