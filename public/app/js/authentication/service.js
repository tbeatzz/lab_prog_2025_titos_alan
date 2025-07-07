const BASE_URL = "http://localhost/lab_prog_2025_titos_alan/public";

export const authenticationService = {
    login: async (data) => {
        const response = await fetch(`${BASE_URL}/authentication/login`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || "Error en la autenticación.");
        }

        return await response.json(); // { message: "OK" }
    },

    logout: async () => {
        return await fetch(`${BASE_URL}/authentication/logout`);
    }
};
