

<head>
    <title>BajoCeroWear | Editar Cuenta</title>
</head>

<section class="mb-4">
    <h1 class="text-center">Editar Cuenta de Usuario</h1>
    <p class="text-center text-muted">
        Modifica los datos de la cuenta seleccionada.
    </p>
</section>

<section class="mb-4 text-center">
    <p id="userEstado" class="mb-1"></p>
    <p id="userFecha" class="mb-1"></p>
</section>

<section class="mb-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form id="editUserForm">
                <input type="hidden" id="id" value="<?= htmlspecialchars($result['id']) ?>">

                <div class="row g-3">
                    <!-- Apellido -->
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control"
                            placeholder="Ingresa el apellido" required minlength="2" maxlength="15"
                            pattern="[A-Za-z\s]+" title="Solo letras y espacios, entre 2 y 50 caracteres" value=""
                            disabled />
                    </div>
                    <!-- Nombres -->
                    <div class="col-md-6">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" id="nombres" name="nombres" class="form-control"
                            placeholder="Ingresa los nombres" required minlength="2" maxlength="50"
                            pattern="[A-Za-z\s]{2,50}" title="Solo letras y espacios, entre 2 y 50 caracteres" value=""
                            disabled />
                    </div>
                    <!-- Cuenta -->
                    <div class="col-md-6">
                        <label for="cuenta" class="form-label">Cuenta</label>
                        <input type="text" id="cuenta" name="cuenta" class="form-control"
                            placeholder="Ingresa el nombre de usuario" required minlength="4" maxlength="15"
                            pattern="[A-Za-z0-9]{4,15}" title="Solo letras y números, entre 4 y 15 caracteres" value=""
                            disabled />
                    </div>
                    <!-- Perfil -->
                    <div class="col-md-6">
                        <label for="perfil" class="form-label">Perfil</label>
                        <select id="perfil" name="perfil" class="form-select" disabled>
                            <option value="operador">Operador</option>
                            <option value="administrador">
                                Administrador
                            </option>
                        </select>
                    </div>
                    <!-- Correo -->
                    <div class="col-12">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" id="correo" name="correo" class="form-control"
                            placeholder="Ingresa el correo electrónico" required
                            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            title="Debe ser un email válido (ejemplo: usuario@dominio.com)" value="" disabled />
                    </div>
                    <!-- Clave -->
                    <div class="col-md-6">
                        <label for="clave" class="form-label">Clave</label>
                        <input type="password" id="clave" name="clave" class="form-control"
                            placeholder="Ingresa la contraseña" required minlength="8" maxlength="20" pattern=".{8,20}"
                            title="Debe tener entre 8 y 20 caracteres" value="" disabled />
                    </div>
                    <!-- Confirmación de la clave -->
                    <div class="col-md-6">
                        <label for="confirmarClave" class="form-label">Confirmación de la clave</label>
                        <input type="password" id="confirmarClave" name="confirmarClave" class="form-control"
                            placeholder="Confirma la contraseña" required minlength="8" maxlength="20" pattern=".{8,20}"
                            title="Debe coincidir con la contraseña y tener entre 8 y 20 caracteres" value=""
                            disabled />
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Botones de accion -->

<section class="d-flex justify-content-center align-items-center flex-column gap-4 ">
    <div class="botones-accion-container col-md-8 d-flex justify-content-between ">

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

<!-- Mensaje -->
<section id="successMessage" class="mt-3 text-center d-none">
    <div class="alert alert-success mb-0" role="alert">Usuario actualizado correctamente<small>nota:cuando se actualiza
            se debera redireccionar al index, pero lo tengo deshabilitado para probrar que el usuario se edita,
            debugeando por consola, viendo si se modifica el dato en el array</small></div>
</section>