<head>
    <title>BajoCeroWear | Editar producto</title>
</head>

<!-- Título principal -->
<section class="mb-4">
    <h1 class="text-center">Editar Producto</h1>
    <p class="text-center text-muted">
        Modifica los datos del producto seleccionado.
    </p>
</section>  

<!-- <section class="mb-4 text-center">
    <p id="itemFecha" class="mb-1"></p>
</section> -->

<!-- Formulario de edición de producto -->
<section class="mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="editItemForm">
                <input type="hidden" id="id" disabled>
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control"
                            placeholder="Ingresa el nombre del producto" required minlength="2" pattern="[A-Za-z\s]+"
                            title="Solo letras y espacios, entre 2 y 50 caracteres" disabled>
                    </div>
                    
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" id="codigo" name="codigo" class="form-control"
                            placeholder="Ingresa el código del producto" required minlength="3" maxlength="10"
                            pattern="[A-Za-z0-9]{3,10}" title="Solo letras y números, entre 3 y 10 caracteres" disabled>
                    </div>
                    <!-- Categoría -->
                    <div class="col-md-6">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select id="categoria" name="categoria" class="form-select" disabled>
                            <option value="">Seleccione una categoría</option>
                        </select>
                    </div>
                    <!-- Precio -->
                    <div class="col-md-6">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" id="precio" name="precio" class="form-control"
                            placeholder="Ingresa el precio" required min="0" max="999999.99"
                            title="Debe ser un número entre 0 y 999999.99" disabled>
                    </div>
                    <!-- Stock -->
                    <div class="col-md-12">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" id="stock" name="stock" class="form-control"
                            placeholder="Ingresa la cantidad en stock" required min="0" max="10000"
                            title="Debe ser un número entero entre 0 y 10000" disabled>
                    </div>
                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control"
                            placeholder="Ingresa una descripción del producto" rows="3" maxlength="500"
                            title="Máximo 500 caracteres" disabled></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Botones de acción -->
<section class="d-flex justify-content-center align-items-center flex-column gap-4">
    <div class="botones-accion-container col-md-8 d-flex justify-content-between">
        <div class="botones-accion d-flex justify-content-center gap-2 p-1">
            <button type="button" class="btn btn-primary" id="editButton">
                Editar <i class="bi bi-pen"></i>
            </button>
            <button type="submit" class="btn btn-success d-none" id="updateButton">
                Actualizar <i class="bi bi-file-earmark-arrow-up"></i>
            </button>
            <button type="button" class="btn btn-warning d-none" id="cancelButton">
                Cancelar <i class="bi bi-x-circle"></i>
            </button>
        </div>
        <div class="botones-accion d-flex justify-content-center gap-2 p-1">
            <button type="button" class="btn btn-outline-primary" id="exportButton">
                Exportar a PDF <i class="bi bi-filetype-pdf"></i>
            </button>
            <button type="button" class="btn btn-danger" id="deleteButton">
                Eliminar registro <i class="bi bi-trash3"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary d-flex align-items-center justify-content-between gap-1" id="volverListadoButton">
                Volver al listado <i class="bi bi-arrow-left-square"></i>
            </button>

           
        </div>
    </div>
</section>

<!-- Mensajes -->
<section id="successMessage" class="mt-3 text-center d-none">
    <div class="alert alert-success mb-0" role="alert">Producto actualizado correctamente</div>
</section>