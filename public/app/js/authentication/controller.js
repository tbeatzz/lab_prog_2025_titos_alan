import { authenticationService } from "./service.js";

export const initLoginController = () => {
    const form = document.getElementById("loginForm");
    if (!form) return;

    const inputs = form.querySelectorAll("input");

    // Validación en tiempo real
    inputs.forEach(input => {
        input.addEventListener("input", () => {
            input.classList.remove("is-invalid");
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "";
            }
            clearLoginError();
        });
    });

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Quitar feedback previo
        inputs.forEach(input => input.classList.remove("is-invalid"));
        clearLoginError();

        // Validación local
        let isValid = true;
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.classList.add("is-invalid");
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains("invalid-feedback")) {
                    if (input.validity.valueMissing) {
                        feedback.textContent = "Este campo es obligatorio.";
                    } else if (input.validity.patternMismatch) {
                        if (input.id === "loginUsuario") {
                            feedback.textContent = "Solo letras sin espacios, entre 2 y 15 caracteres.";
                        } else if (input.id === "loginContrasenha") {
                            feedback.textContent = "Debe tener entre 8 y 20 caracteres.";
                        }
                    }
                }
                isValid = false;
            }
        });

        if (!isValid) return;

        // Datos del formulario
        const data = {
            userName: document.getElementById("loginUsuario").value,
            password: document.getElementById("loginContrasenha").value
        };

        try {
            const response = await authenticationService.login(data);
            if (response.message === "OK") {
                window.location.href = "home/index";
            } else {
                showLoginError(response.message || "Error en la autenticación.");
            }
        } catch (error) {
            // Podés usar solo uno de estos (o ambos si querés)
            showSweetAlertError(error.message || "Error en la autenticación.");
            // showLoginError(error.message || "Error en la autenticación."); // opcional
        }
    });
};

// 🔴 SweetAlert2: error emergente
function showSweetAlertError(message) {
    Swal.fire({
        icon: "error",
        title: "Error de autenticación",
        text: message,
        confirmButtonText: "Aceptar"
    });
}

// 🟥 Error debajo del formulario (fallback opcional)
function showLoginError(message) {
    let errorDiv = document.getElementById("loginErrorMsg");

    if (!errorDiv) {
        errorDiv = document.createElement("div");
        errorDiv.id = "loginErrorMsg";
        errorDiv.className = "alert alert-danger mt-3";
        const form = document.getElementById("loginForm");
        form.appendChild(errorDiv);
    }

    errorDiv.textContent = message;
    errorDiv.style.display = "block";
}

function clearLoginError() {
    const errorDiv = document.getElementById("loginErrorMsg");
    if (errorDiv) {
        errorDiv.style.display = "none";
        errorDiv.textContent = "";
    }
}
