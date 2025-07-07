import { authenticationService } from "./service.js";

export const initLoginController = () => {
    const form = document.getElementById("loginForm");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const data = {
            userName: document.getElementById("loginUsuario").value,
            password: document.getElementById("loginContrasenha").value
        };

        try {
            const response = await authenticationService.login(data);
            if (response.message === "OK") {
                window.location.href = "home/index"; // redirige al home
            }
        } catch (error) {
            alert(error.message || "Error en el login.");
            console.error(error);
        }
    });
};
