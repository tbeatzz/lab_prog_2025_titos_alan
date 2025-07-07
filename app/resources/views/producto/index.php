<head>
    <title>BajoCeroWear | Productos</title>
</head>
<div class="container py-4">
    <!-- Título principal -->
    <section class="mb-4">
        <h1 class="text-center">Gestión de Productos</h1>
        <p class="text-center text-muted">Administra los productos del sistema BajoCeroWear.</p>
    </section>

    <!-- Botones de acción -->
    <section class="mb-4 d-flex justify-content-center gap-3">
        <button id="botonCreateItem" class="btn btn-primary">
            Alta de nuevo producto <i class="bi bi-plus-circle"></i>
        </button>
        <button id="botonExportPdfItems" class="btn btn-outline-secondary">
            Exportar listado en PDF <i class="bi bi-filetype-pdf"></i>
        </button>
    </section>

    <!-- Filtros -->
    <section class="mb-4">
        <h5>Filtros</h5>
        <div class="row g-3">
            <!-- Filtrar por categoría -->
            <div class="col-md-4">
                <label for="filterCategory" class="form-label">Categoría</label>
                <select id="filterCategory" class="form-select">
                    <!-- Opciones dinámicas -->
                </select>
            </div>

            <!-- Buscar por nombre -->
            <div class="col-md-4">
                <label for="filterName" class="form-label">Nombre</label>
                <input type="text" id="filterName" class="form-control" placeholder="Ingresa un nombre">
            </div>

            <!-- Buscar por código -->
            <div class="col-md-4">
                <label for="filterCode" class="form-label">Código</label>
                <input type="text" id="filterCode" class="form-control" placeholder="Código del producto">
            </div>

   

            <!-- Ordenar por -->
            <div class="col-md-4">
                <label for="filterOrden" class="form-label">Ordenar por</label>
                <select id="filterOrden" class="form-select">
                    <option value="">Ninguno</option>
                    <option value="nombre_asc">Nombre (A-Z)</option>
                    <option value="nombre_desc">Nombre (Z-A)</option>
                    <option value="precio_asc">Precio (menor a mayor)</option>
                    <option value="precio_desc">Precio (mayor a menor)</option>
                    <option value="stock_asc">Stock (menor a mayor)</option>
                    <option value="stock_desc">Stock (mayor a menor)</option>
                </select>
            </div>

            <!-- Botón aplicar -->
            <div class="col-md-4 d-flex align-items-end">
                <button id="botonItemFiltros" class="btn btn-primary w-100">Aplicar filtros</button>
            </div>

            <!-- Botón limpiar -->
            <div class="col-md-4 d-flex align-items-end">
                <button id="botonLimpiarFiltros" class="btn btn-secondary w-100">Limpiar</button>
            </div>
        </div>
    </section>


    <!-- Tabla de productos -->
    <section>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle" id="productTable">
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
            <div class="mt-3 d-flex justify-content-center gap-2 align-items-center">
                    <button id="prevPage" class="btn btn-outline-secondary">Anterior</button>
                    <span id="currentPage">Página 1</span>
                    <button id="nextPage" class="btn btn-outline-secondary">Siguiente</button>
                </div>
        </div>
    </section>

</div>
