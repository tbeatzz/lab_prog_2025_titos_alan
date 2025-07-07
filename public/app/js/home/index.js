


import { homeController } from './controller.js';

document.addEventListener('DOMContentLoaded', () => {
    cargarCantidadUsuarios();
    cargarCantidadProductos();
    cargarCantidadCategorias();
    configurarBotones();
    cargarHora();
});

const cargarHora = () => {
    document.getElementById("fecha-hoy").innerText = new Date().toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
        });
};

const cargarCantidadUsuarios = () => {
    homeController.cargarCantidadUsuarios();
}

const cargarCantidadProductos = () => {
    homeController.cargarCantidadProductos();
}

const cargarCantidadCategorias = () => {
    homeController.cargarCantidadCategorias();
}

const configurarBotones = () =>{
    const buttonCategorias = document.getElementById('buttonCategorias');
    const buttonProductos = document.getElementById('buttonProductos');
    const buttonUsuarios = document.getElementById('buttonUsuarios');

    buttonCategorias.addEventListener('click', () =>{
        window.location.href = `categoria/index`;
    })
    buttonProductos.addEventListener('click', () =>{
        window.location.href = `producto/index`;
    })
    buttonUsuarios.addEventListener('click', () =>{
        window.location.href = `usuario/index`;
    })
}