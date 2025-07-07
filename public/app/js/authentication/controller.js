import { authenticationService } from "./service.js";

export const initLoginController = () => {
    const form = document.getElementById("loginForm");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Quitar feedback previo
        const inputs = form.querySelectorAll("input");
        inputs.forEach(input => input.classList.remove("is-invalid"));
        clearLoginError();

        // Validación local
        if (!form.checkValidity()) {
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.classList.add("is-invalid");
                }
            });
            return;
        }

        const data = {
            userName: document.getElementById("loginUsuario").value,
            password: document.getElementById("loginContrasenha").value
        };

        try {
            const response = await authenticationService.login(data);
            if (response.message === "OK") {
                window.location.href = "home/index";
            }else{
                showLoginError(response.error || "Error en la autenticación.")
            }
        } catch (error) {
            // Mostrar el mensaje enviado desde el backend en un div debajo del formulario
            showLoginError(error.message || "Error en la autenticación.");
        }
    });
};

// Función para mostrar error general de login (puede ser un div en el formulario)
function showLoginError(message) {
    let errorDiv = document.getElementById("loginErrorMsg");

    if (!errorDiv) {
        // Si no existe el div, lo creamos y lo insertamos
        errorDiv = document.createElement("div");
        errorDiv.id = "loginErrorMsg";
        errorDiv.className = "alert alert-danger mt-3";
        const form = document.getElementById("loginForm");
        form.appendChild(errorDiv);
    }

    errorDiv.textContent = message;
}
function clearLoginError() {
    const errorDiv = document.getElementById("loginErrorMsg");
    if (errorDiv) {
        errorDiv.style.display = "none";
        errorDiv.textContent = "";
    }
}