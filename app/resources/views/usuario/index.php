<head>
    <title>BajoCeroWear | Usuarios</title>
</head>


<div class="container py-4">

    <section class="mb-4">
        <h1 class="text-center">Gestión de Usuarios</h1>
        <p class="text-center text-muted">Administra las cuentas de usuarios del sistema BajoCeroWear.</p>
    </section>


    <section class="mb-4 d-flex justify-content-center gap-3">
        <button id="botonCreateUser" class="btn btn-primary">Alta de nueva cuenta <i class="bi bi-person-add"></i></a>
            <button id="exportPdfButton" class="btn btn-outline-secondary">Exportar listado en PDF <i class="bi bi-filetype-pdf"></i></a>
    </section>


    <section class="mb-4">
        <h5>Filtros</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="filterProfile" class="form-label">Filtrar por perfil</label>
                <select id="filterProfile" class="form-select">
                    <option value="">Todos los perfiles</option>
                    <option value="administrador">Administrador</option>
                    <option value="operador">Operador</option>

                </select>
            </div>
            <div class="col-md-4">
                <label for="filterEmail" class="form-label">Buscar por correo</label>
                <input type="email" id="filterEmail" class="form-control" placeholder="Ingresa un correo">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="botonFiltros" class="btn btn-primary w-100">Aplicar filtros</button>
            </div>
        </div>
    </section>


    <section>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle" id="userTable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Cuenta</th>
                        <th scope="col">Perfil</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </section>
</div>