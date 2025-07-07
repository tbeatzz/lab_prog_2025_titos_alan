
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="http://localhost/lab_prog_2025_titos_alan/public/">


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Página de login">

    <title>BajoCeroWear | Cerrar session</title>
    <link rel="icon" type="image/x-icon" href="/lab_prog_2025_titos_alan/public/app/assets/images/favicon.ico">

    <!-- Bootstrap CSS -->
    <link type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <!--  CSS y JS -->
    <link rel="stylesheet" href="/lab_prog_2025_titos_alan/public/app/css/main.css">

    <script type="module" src="app/js/authentication/logout.js"></script>


</head>
<body id="login" class="d-flex justify-content-center align-items-center min-vh-100">

    <main class="login-container p-4 rounded bg-white shadow-sm">
        
        <div class="container py-5 d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 80vh;">
            <div class="text-center login-logo mb-4">
            <img class="border rounded-circle" src="/lab_prog_2025_titos_alan/public/app/assets/images/main_logo.webp" alt="Logo BajoCeroWear">
        </div>
            <div class="mb-4">
                <i class="bi bi-box-arrow-right display-1 text-danger"></i>
            </div>

            <h2 class="mb-3">Cerrando sesión...</h2>
            <p class="text-muted mb-4">Gracias por utilizar el sistema BajoCeroWear. Esperamos verte pronto.</p>

            <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>

            <!-- Opción manual por si la redirección automática falla -->
            <p class="mt-4 small text-muted">
                Si no sos redirigido automáticamente, <a href="authentication/index">haz clic aquí para iniciar sesión nuevamente</a>.
            </p>
        </div>

       
    </main>

</body>
</html>
