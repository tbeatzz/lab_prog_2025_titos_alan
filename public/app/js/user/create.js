import { userController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    
    //formulario
    const form = document.getElementById('createUserForm');

    //resetear el formulario
    userController.resetForm('createUserForm');

    //
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        userController.save();
    });
});