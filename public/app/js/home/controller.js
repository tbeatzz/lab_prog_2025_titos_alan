import { homeService } from './service.js';


export const homeController = {
    async cargarCantidadUsuarios() {
        const elemento = document.getElementById('cantidad-usuarios');

        if (elemento) {
            try {
                const response = await homeService.getCantidadUsuarios();
                elemento.textContent = response.result;
            } catch (error) {
                console.error('Error al cargar cantidad de usuarios:', error);
                elemento.textContent = 'Error';
            }
        } else {
            // Si el usuario es Operador, no existe el <strong>, pero sí el contenedor
            const fallback = document.getElementById('usuariosCount');
            if (fallback) {
                fallback.innerHTML = '<strong>No disponible</strong>';
            } else {
                console.warn('Ni #cantidad-usuarios ni #usuariosCount existen.');
            }
        }
    },




    async cargarCantidadProductos() {
        try {
            const response = await homeService.getCantidadProductos();
            document.getElementById('cantidad-productos').textContent = response.result;
        } catch (error) {
            console.error('Error al cargar cantidad de productos:', error);
            document.getElementById('cantidad-productos').textContent = 'Error';
        }
    },

    async cargarCantidadCategorias() {
        try {
            const response = await homeService.getCantidadCategorias();
            document.getElementById('cantidad-categorias').textContent = response.result;
        } catch (error) {
            console.error('Error al cargar cantidad de categorias:', error);
            document.getElementById('cantidad-categorias').textContent = 'Error';
        }
    },




}


