const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const productoService = {

    load: async (id) => {
        const res = await fetch(`${BASE_URL}/producto/load/${id}`);
        if (!res.ok) throw new Error("Error al cargar producto");
        return res.json();
    },

    save: async (producto) => {
        const response = await fetch(`${BASE_URL}/producto/save`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(producto),
        });
        return response.json();
    },

    update: async (producto) => {
        try {
            const response = await fetch(`${BASE_URL}/producto/update/${producto.id}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(producto),
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${result.message || response.statusText}`);
            }

            return result;
        } catch (error) {
            console.error("Error en productoService.update:", error);
            throw error;
        }
    },

    async delete(id) {
        const response = await fetch(`producto/delete/${id}`, { method: 'GET' });
        const data = await response.json();
        return data;
    },

    list: async (filters = {}) => {
        const response = await fetch(`${BASE_URL}/producto/list`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(filters),
        });

        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }

        return response.json();
    },

    exportPdf: async (filters = {}) => {
        const response = await fetch(`${BASE_URL}/producto/exportPdf`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(filters),
        });

        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `productos_${new Date().toISOString().replace(/[:.]/g, "")}.pdf`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    },

    exportSinglePdf: async (id) => {
        window.location.href = `${BASE_URL}/producto/exportSinglePdf/${id}`;
    },

   


};