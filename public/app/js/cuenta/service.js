const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const cuentaService = {
    loadMisDatos: async () => {
        const response = await fetch(`${BASE_URL}/cuenta/misDatos`, {
            credentials: 'include'
        });
        return response.json();
    },

    changePassword: async (newPassword) => {
        const response = await fetch(`${BASE_URL}/cuenta/editPassword`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ newPassword }),
            credentials: 'include' //
        });
        return response.json();
    },

    updateDatos: async (datos) => {
        const response = await fetch(`${BASE_URL}/cuenta/editDatos`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos),
            credentials: 'include'
        });
        return response.json();
    },

};
