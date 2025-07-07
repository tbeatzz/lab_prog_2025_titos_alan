<section class="mb-4 text-center">

    <p id="userFecha" class="mb-1"></p>
</section>
    <!-- Título -->
    <section class="mb-4 text-center">
        
        <h1>Mi Perfil</h1>
        <p class="text-muted">Consulta o modifica tu información personal en el sistema BajoCeroWear.</p>
    </section>

    <!-- Formulario -->
    <section class="mb-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form id="perfilForm">
                    <input type="hidden" id="id" value="<?= htmlspecialchars($result['id'] ?? '') ?>">

                    <div class="row g-3">
                        <!-- Apellido -->
                        <div class="col-md-6">
                            <label for="apellidos" class="form-label">Apellido</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control" disabled>
                        </div>

                        <!-- Nombres -->
                        <div class="col-md-6">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" id="nombres" name="nombres" class="form-control" disabled>
                        </div>

                        <!-- Cuenta -->
                        <div class="col-md-6">
                            <label for="cuenta" class="form-label">Cuenta</label>
                            <input type="text" id="cuenta" name="cuenta" class="form-control" disabled>
                        </div>

                        <!-- Perfil -->
                        <div class="col-md-6">
                            <label for="perfil" class="form-label">Perfil</label>
                            <input type="text" id="perfil" name="perfil" class="form-control" disabled>
                        </div>

                        <!-- Correo -->
                        <div class="col-12">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" id="correo" name="correo" class="form-control" disabled>
                        </div>

                        <!-- Clave -->
                        <div class="col-md-6">
                            <label for="clave" class="form-label">Nueva contraseña</label>
                            <input type="password" id="clave" name="clave" class="form-control" disabled>
                        </div>

                        <!-- Confirmar Clave -->
                        <div class="col-md-6">
                            <label for="confirmarClave" class="form-label">Confirmar contraseña</label>
                            <input type="password" id="confirmarClave" name="confirmarClave" class="form-control" disabled>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <button type="button" id="btnEditar" class="btn btn-primary">
                            Editar datos <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="submit" id="btnGuardar" class="btn btn-success d-none">
                            Guardar cambios <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" id="btnCancelar" class="btn btn-secondary d-none">
                            Cancelar <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
