import { categoriaController } from "../categoria/controller.js"; 


document.addEventListener('DOMContentLoaded', async () => {

  // Obtener el formulario y los campos
  const form = document.getElementById("createCatForm");
  const elements = {
    categoria: document.getElementById("categoria"),
  };

  // Verificar si el formulario y los campos existen
  if (!form) {
    console.error('Formulario con ID "createCatForm" no encontrado en el DOM');
    alert("Error: No se encontró el formulario de creación");
    window.location.href = "categorias/index";
    return;
  }

  for (const [key, element] of Object.entries(elements)) {
    if (!element && key !== "successMessage") {
      // successMessage puede no existir inicialmente
      console.error(`Elemento con ID "${key}" no encontrado en el DOM`);
      alert(`Error: No se encontró el elemento "${key}" en el formulario`);
      window.location.href = "categoria/index";
      return;
    }
  }

  // Resetear el formulario
  categoriaController.resetForm("createCatForm");

  // Manejar el envío del formulario
  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Validaciones
    const errors = [];
    if (!elements.categoria.value.trim()) errors.push("El nombre es requerido");

    if (errors.length > 0) {
      alert(
        "Por favor corrige los siguientes errores:\n- " + errors.join("\n- ")
      );
      return;
    }

    try {
      const savedCat = await categoriaController.save(); 
      if (savedCat && elements.successMessage) {
        elements.successMessage.classList.remove("d-none");
      }
    } catch (error) {
      console.error("Error al guardar la categoria:", error);
      alert("Error al guardar la categoria: " + error.message);
    }
  });
});



