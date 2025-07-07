<head>
	<title>BajoCeroWear | Crar Categoria</title>
</head>

<section class="mb-4">
	<h1 class="text-center">Crear Nueva Categoria</h1>
	<p class="text-center text-muted">
		Completa el formulario para registrar una nueva categoria en el
		sistema.
	</p>
</section>


<section class="mb-4">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<form id="createCatForm">
				<div class="row g-3">
					<!-- Cateogria -->
					<div class="col-md-12">
						<label for="categoria" class="form-label">Nombre categoria</label>
						<input type="text" id="categoria" name="categoria" class="form-control"
							placeholder="Ingresa el nombre de la categoria" required minlength="2" maxlength="15"
							pattern="[A-Za-z\s]+" title="Solo letras y espacios, entre 2 y 50 caracteres">
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<!-- Botones de acción -->
<section class="d-flex justify-content-center flex-wrap gap-3">
	<button type="submit" form="createCatForm"
		class="btn btn-primary d-flex align-items-center justify-content-between gap-1">
		Validar y guardar <i class="bi bi-save"></i>
	</button>
	<a href="./user/index"
		class="btn btn-outline-secondary d-flex align-items-center justify-content-between gap-1">Volver al
		listado <i class="bi bi-arrow-left-square"></i></a>
</section>

<section id="successMessage" class="mt-3 text-center d-none">
	<div class="alert alert-success mb-0" role="alert">Registro creado</div>
</section>