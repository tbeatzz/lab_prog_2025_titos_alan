const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const categoriaService = {
    list: async () => {
        const res = await fetch(`${BASE_URL}/categoria/list`, {
            method: "get",
            headers: {
                "Accept": "application/json"
            }
        });
        if (!res.ok) throw new Error("Error al cargar categorías");
        const response = await res.json();
        return response.result;
    }
};
