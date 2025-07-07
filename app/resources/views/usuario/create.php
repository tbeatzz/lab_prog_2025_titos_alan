<head>
	<title>BajoCeroWear | Crar usuario</title>
</head>

<section class="mb-4">
	<h1 class="text-center">Crear Nueva Cuenta</h1>
	<p class="text-center text-muted">
		Completa el formulario para registrar un nuevo usuario en el
		sistema.
	</p>
</section>


<section class="mb-4">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<form id="createUserForm">
				<div class="row g-3">
					<!-- Apellido -->
					<div class="col-md-6">
						<label for="apellido" class="form-label">Apellidos</label>
						<input type="text" id="apellidos" name="apellidos" class="form-control"
							placeholder="Ingresa el apellido" required minlength="2" maxlength="15"
							pattern="[A-Za-z\s]+" title="Solo letras y espacios, entre 2 y 50 caracteres">
					</div>
					<!-- Nombres -->
					<div class="col-md-6">
						<label for="nombres" class="form-label">Nombres</label>
						<input type="text" id="nombres" name="nombres" class="form-control"
							placeholder="Ingresa los nombres" required minlength="2" maxlength="50"
							pattern="[A-Za-z\s]{2,50}" title="Solo letras y espacios, entre 2 y 50 caracteres">
					</div>
					<!-- Cuenta -->
					<div class="col-md-6">
						<label for="cuenta" class="form-label">Cuenta</label>
						<input type="text" id="cuenta" name="cuenta" class="form-control"
							placeholder="Ingresa el nombre de usuario" required minlength="4" maxlength="15"
							pattern="[A-Za-z0-9]{4,15}" title="Solo letras y números, entre 4 y 15 caracteres">
					</div>
					<!-- Perfil -->
					<div class="col-md-6">
						<label for="perfil" class="form-label">Perfil</label>
						<select id="perfil" name="perfil" class="form-select" required>
							<option value="" disabled selected>Selecciona un perfil</option>
							<option value="operador">Operador</option>
							<option value="administrador">Administrador</option>
						</select>
					</div>
					<!-- Correo -->
					<div class="col-12">
						<label for="correo" class="form-label">Correo</label>
						<input type="email" id="correo" name="correo" class="form-control"
							placeholder="Ingresa el correo electrónico" required
							pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
							title="Debe ser un email válido (ejemplo: usuario@dominio.com)">
					</div>
					<!-- Clave -->
					<div class="col-md-6">
						<label for="clave" class="form-label">Clave</label>
						<input type="password" id="clave" name="clave" class="form-control"
							placeholder="Ingresa la contraseña" required minlength="8" maxlength="20" pattern=".{8,20}"
							title="Debe tener entre 8 y 20 caracteres">
					</div>
					<!-- Confirmación de la clave -->
					<div class="col-md-6">
						<label for="confirmarClave" class="form-label">Confirmación de la clave</label>
						<input type="password" id="confirmarClave" name="confirmarClave" class="form-control"
							placeholder="Confirma la contraseña" required minlength="8" maxlength="20" pattern=".{8,20}"
							title="Debe coincidir con la contraseña y tener entre 8 y 20 caracteres">
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<!-- Botones de acción -->
<section class="d-flex justify-content-center flex-wrap gap-3">
	<button type="submit" form="createUserForm"
		class="btn btn-primary d-flex align-items-center justify-content-between gap-1">
		Validar y guardar <i class="bi bi-save"></i>
	</button>
	<a href="./usuario/index"
		class="btn btn-outline-secondary d-flex align-items-center justify-content-between gap-1">Volver al
		listado <i class="bi bi-arrow-left-square"></i></a>
</section>

<section id="successMessage" class="mt-3 text-center d-none">
	<div class="alert alert-success mb-0" role="alert">Registro creado</div>
</section>