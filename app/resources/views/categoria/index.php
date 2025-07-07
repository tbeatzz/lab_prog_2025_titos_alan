<head>
    <title>BajoCeroWear | Categorias</title>
</head>


<div class="container py-4">

    <!-- Título principal -->
    <section class="mb-4">
        <h1 class="text-center">Categorias del sistema</h1>
        <p class="text-center text-muted">Administra las categorias asociados a los productos del sistema BajoCeroWear.</p>
    </section>

    <!-- Botones de acción -->

    <section class="mb-4 d-flex justify-content-center gap-3">
        <button id="botonCreateCat" class="btn btn-primary">Alta de nueva categoria <i class="bi bi-plus-circle"></i></a>
            <button id="exportPdfButton" class="btn btn-outline-secondary">Exportar listado en PDF <i class="bi bi-filetype-pdf"></i></a>
    </section>

    <!-- Filtros -->
    <section class="mb-4">
        <h5>Filtros</h5>
        <div class="row g-3">

            <div class="col-md-6">
                <label for="filterName" class="form-label">Buscar por nombre</label>
                <input type="text" id="filterName" class="form-control" placeholder="Ingresa un nombre">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button id="botonCatFiltros" class="btn btn-primary w-100">Aplicar filtros</button>
            </div>
        </div>
    </section>

    <!-- Tabla de productos -->
    <section>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle " id="catTable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>
</div>