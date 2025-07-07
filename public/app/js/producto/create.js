import { productoController } from "./controller.js";
import { categoriaController } from "../categoria/controller.js"; 


document.addEventListener('DOMContentLoaded', async () => {
    await categoriaController.cargarOpcionesSelect('categoria');
  
    // Obtener el formulario y los campos
    const form = document.getElementById("createItemForm");
    const elements = {
      nombre: document.getElementById("nombre"),
      codigo: document.getElementById("codigo"),
      categoria: document.getElementById("categoria"),
      precio: document.getElementById("precio"),
      stock: document.getElementById("stock"),
      descripcion: document.getElementById("descripcion"),
      successMessage: document.getElementById("successMessage"),
    };
  
    // Verificar si el formulario y los campos existen
    if (!form) {
      console.error('Formulario con ID "createItemForm" no encontrado en el DOM');
      await Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se encontró el formulario de creación',
      });
      window.location.href = "producto/index";
      return;
    }
  
    for (const [key, element] of Object.entries(elements)) {
      if (!element && key !== "successMessage") {
        console.error(`Elemento con ID "${key}" no encontrado en el DOM`);
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: `No se encontró el elemento "${key}" en el formulario`,
        });
        window.location.href = "producto/index";
        return;
      }
    }
  
    // Resetear el formulario
    productoController.resetForm("createItemForm");
  
    // Manejar el envío del formulario
    form.addEventListener("submit", async (event) => {
      event.preventDefault();
  
      // Validaciones
      const errors = [];
      if (!elements.nombre.value.trim()) errors.push("El nombre es requerido");
      if (!elements.codigo.value.trim()) errors.push("El código es requerido");
      if (!elements.categoria.value.trim())
        errors.push("La categoría es requerida");
      if (!elements.precio.value || elements.precio.value <= 0)
        errors.push("El precio debe ser mayor que 0");
      if (!elements.stock.value || elements.stock.value < 0)
        errors.push("El stock no puede ser negativo");
  
      if (errors.length > 0) {
        await Swal.fire({
          icon: 'warning',
          title: 'Errores en el formulario',
          html: errors.map(e => `• ${e}`).join('<br>'),
        });
        return;
      }
  
      try {
        const savedItem = await productoController.save(); 
        if (savedItem) {
          await Swal.fire({
            icon: 'success',
            title: '¡Producto creado!',
            text: 'El producto fue guardado exitosamente.',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
          });
          // Opcional: limpiar formulario o redirigir
          productoController.resetForm("createItemForm");
          if (elements.successMessage) {
            window.location.href = `producto/index`;
          }
        }
      } catch (error) {
        console.error("Error al guardar el item:", error);
        await Swal.fire({
          icon: 'error',
          title: 'Error al guardar',
          text: error.message || 'Ocurrió un error inesperado',
        });
      }
    });
});
