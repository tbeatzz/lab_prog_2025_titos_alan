<?php

   

    if ($_SESSION["perfil"] == "Operador"){
        $esOperador = true;
    }else{
        $esOperador = false;
    }
?>

<head>
    <title>BajoCeroWear | Sistema de gestion</title>
</head>

<div class="container py-4">
    <section class="home">
        <!-- Saludo -->
        <div class="alert alert-primary d-flex justify-content-between align-items-center">
            <h4  style="margin: 0;" class="alert-heading">¡Bienvenido, <?= $_SESSION["usuario"] ?>! </h4>
            <p style="margin: 0;">Hoy es <span id="fecha-hoy"></span>.</p>
        </div>

        <!-- Estadísticas -->
        <div class="row text-center ">
            <div class="col-md-4 pt-3">
                <div id="usuariosCount" class="alert alert-info">Usuarios registrados: <?= !$esOperador ? "<strong id='cantidad-usuarios'></strong>" : "";?> </div>
            </div>
            <div class="col-md-4 pt-3">
                <div class="alert alert-success">Productos cargados: <strong id="cantidad-productos"></strong></div>
            </div>
            <div class="col-md-4 pt-3">
                <div class="alert alert-warning">Categorías cargadas: <strong id="cantidad-categorias"></strong></div>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="row">
            <!-- Usuarios -->
            <div class="col-md-4 pt-3">
                <div class="card text-center shadow-sm <?= $esOperador ? "opacity-50 pointer-events-none" : "" ?>">
                    <div class="card-body">
                        <i class="bi bi-people-fill display-4 text-primary"></i>
                        <h5 class="card-title mt-2">Gestión de Usuarios</h5>
                        
                        <button id="buttonUsuarios" class="btn btn-primary mt-2" <?= $esOperador ? "disabled" : "" ?>>Ir</button>
                    </div>
                </div>
            </div>

            <!-- Productos -->
            <div class="col-md-4 pt-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-box-seam display-4 text-success"></i>
                        <h5 class="card-title mt-2">Gestión de Productos</h5>
                        <button id="buttonProductos" class="btn btn-success mt-2">Ir</button>
                    </div>
                </div>
            </div>
            <!-- Categorías -->
            <div class="col-md-4 pt-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-tags-fill display-4 text-warning"></i>
                        <h5 class="card-title mt-2">Gestión de Categorías</h5>
                       
                        <button id="buttonCategorias" class="btn btn-warning text-white mt-2">Ir</button>
                    </div>
                </div>
            </div>
        </div>
</div>


</section>


</div>