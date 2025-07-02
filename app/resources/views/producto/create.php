<section class="mb-4">
    <h1 class="text-center">Agregar Nuevo Producto</h1>
    <p class="text-center text-muted">Completa el formulario para registrar un nuevo producto en el sistema.
    </p>
</section>


<section class="mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="createItemForm">
                <div class="row g-3">
                    <!-- Nombre -->
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control"
                            placeholder="Ingresa el nombre del producto" required minlength="2" pattern="[A-Za-z\s]+"
                            title="Solo letras y espacios, entre 2 y 50 caracteres">
                    </div>
                    <!-- Código -->
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" id="codigo" name="codigo" class="form-control"
                            placeholder="Ingresa el código del producto" required minlength="3" maxlength="10"
                            pattern="[A-Za-z0-9]{3,10}" title="Solo letras y números, entre 3 y 10 caracteres">
                    </div>
                    <!-- Categoría -->
                    <div class="col-md-6">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select id="categoria" name="categoria" class="form-select" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <option value="Ropa Nueva">Ropa Nueva</option>
                            <option value="Ropa Usada">Ropa Usada</option>
                            <option value="Accesorios">Accesorios</option>
                        </select>
                    </div>
                    <!-- Precio -->
                    <div class="col-md-6">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" id="precio" name="precio" class="form-control"
                            placeholder="Ingresa el precio" required min="0" max="999999.99"
                            title="Debe ser un número entre 0 y 999999.99">
                    </div>
                    <!-- Stock -->
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" id="stock" name="stock" class="form-control"
                            placeholder="Ingresa la cantidad en stock" required min="0" max="10000" step="1"
                            title="Debe ser un número entero entre 0 y 10000">
                    </div>
                    <!-- Descripción -->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control"
                            placeholder="Ingresa una descripción del producto" rows="3" maxlength="500"
                            title="Máximo 500 caracteres"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Botones de acción -->
<section class="d-flex justify-content-center flex-wrap gap-3">
    <button type="submit" form="createItemForm"
        class="btn btn-primary d-flex align-items-center justify-content-between gap-1">
        Validar y guardar <i class="bi bi-save"></i>
    </button>
    <a href="./items/index.html"
        class="btn btn-outline-secondary d-flex align-items-center justify-content-between gap-1">Volver al
        listado <i class="bi bi-arrow-left-square"></i></a>
</section>

<section id="successMessage" class="mt-3 text-center d-none">
    <div class="alert alert-success mb-0" role="alert">item agregado</div>
</section>