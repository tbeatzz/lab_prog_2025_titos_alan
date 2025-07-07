<head>
    <title>BajoCeroWear | Editar categoria</title>
</head>

<!-- Título principal -->
<section class="mb-4">
    <h1 class="text-center">Editar Categoria</h1>
    <p class="text-center text-muted">
        Modifica los datos de la categoria seleccionado.
    </p>
</section>  

<!-- <section class="mb-4 text-center">
    <p id="itemFecha" class="mb-1"></p>
</section> -->

<!-- Formulario de edición de categoria -->
<section class="mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="editItemForm">
                <input type="hidden" id="id" disabled>
                <div class="row g-3">
                    <!-- Nombre categoria -->
                    <div class="col-md-12">
                        <label for="categoria" class="form-label">Nombre Categoria</label>
                        <input type="text" id="categoria" name="nombre" class="form-control"
                            placeholder="Ingresa el nombre de la categoria" required minlength="2" pattern="[A-Za-z\s]+"
                            title="Solo letras y espacios, entre 2 y 50 caracteres" disabled>
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
            <a href="./categoria/index" class="btn btn-outline-secondary d-flex align-items-center justify-content-between gap-1">
                Volver al listado <i class="bi bi-arrow-left-square"></i>
            </a>
        </div>
    </div>
</section>

<!-- Mensajes -->
<section id="successMessage" class="mt-3 text-center d-none">
    <div class="alert alert-success mb-0" role="alert">Producto actualizado correctamente</div>
</section>