<head>
    <title>BajoCeroWear | Productos</title>
</head>

<!-- Título principal -->
<section class="mb-4">
    <h1 class="text-center">Gestión de Productos</h1>
    <p class="text-center text-muted">Administra los productos del sistema BajoCeroWear.</p>
</section>

<!-- Botones de acción -->

<section class="mb-4 d-flex justify-content-center gap-3">
    <button id="botonCreateItem" class="btn btn-primary">Alta de nuevo producto <i
            class="bi bi-plus-circle"></i></a>
        <button id="botonExportPdfItems" class="btn btn-outline-secondary">Exportar listado en PDF <i
                class="bi bi-filetype-pdf"></i></a>
</section>

<!-- Filtros -->
<section class="mb-4">
    <h5>Filtros</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="filterCategory" class="form-label">Filtrar por categoría</label>
            <select id="filterCategory" class="form-select">
                <option value="">Todas las categorías</option>
                <option value="Ropa Nueva">Ropa Nueva</option>
                <option value="Ropa Usada">Ropa Usada</option>
                <option value="Accesorios">Accesorios</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="filterName" class="form-label">Buscar por nombre</label>
            <input type="text" id="filterName" class="form-control" placeholder="Ingresa un nombre">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button id="botonItemFiltros" class="btn btn-primary w-100">Aplicar filtros</button>
        </div>
    </div>
</section>

<!-- Tabla de productos -->
<section>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm align-middle " id="itemTable">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Código</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</section>