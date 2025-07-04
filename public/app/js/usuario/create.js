
import { usuarioController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    
    //formulario
    const form = document.getElementById('createUserForm');

    // resetear el formulario
    usuarioController.resetForm(form);

    //
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        usuarioController.save();
    });
});