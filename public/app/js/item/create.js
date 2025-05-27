

import { itemController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    
    //formulario
    const form = document.getElementById('createItemForm');

    //resetear el formulario
    itemController.resetForm('createItemForm');

    //
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        itemController.save();
    });
});