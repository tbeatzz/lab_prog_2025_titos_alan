const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const itemService = {
    list: async () => {
        const res = await fetch(`${BASE_URL}/producto/list`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({}) // Si hay filtros, los agregás acá
        });
        if (!res.ok) throw new Error("Error al obtener productos");
        const json = await res.json();
        return json.result || [];
    },

    load: async (id) => {
        const res = await fetch(`${BASE_URL}/producto/load`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ id })
        });
        if (!res.ok) throw new Error("Error al cargar producto");
        const json = await res.json();
        return json.result;
    },

    save: async (item) => {
        const res = await fetch(`${BASE_URL}/producto/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(item)
        });

        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.message || 'Error al guardar el producto');
        }

        const response = await res.json();
        return response;
    },

    update: async (item) => {
        const res = await fetch(`${BASE_URL}/producto/update`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(item)
        });
        if (!res.ok) throw new Error("Error al actualizar producto");
        return await res.json();
    },

    delete: async (id) => {
        const res = await fetch(`${BASE_URL}/producto/delete`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ id })
        });
        if (!res.ok) throw new Error("Error al eliminar producto");
        return await res.json();
    }
};
