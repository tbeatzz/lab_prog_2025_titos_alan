const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const homeService = {
    getCantidadUsuarios: async () => {
        const response = await fetch(`${BASE_URL}/usuario/cantidadUsuarios` , {
             credentials: 'include'
        });
        if (!response.ok) throw new Error('Error al obtener la cantidad de usuarios');
        return await response.json();
    },
    getCantidadProductos: async () => {
        const response = await fetch(`${BASE_URL}/producto/cantidadProductos`);
        if (!response.ok) throw new Error('Error al obtener la cantidad de productos');
        return await response.json();
    },
    getCantidadCategorias: async () => {
        const response = await fetch(`${BASE_URL}/categoria/cantidadCategorias`);
        if (!response.ok) throw new Error('Error al obtener la cantidad de productos');
        return await response.json();
    }
}

