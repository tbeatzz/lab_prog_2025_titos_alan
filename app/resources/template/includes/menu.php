 <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
     <div class="container-fluid">

         <a class="navbar-brand" href="home/index">
            <img src="<?= APP_URL ?>/app/assets/images/main_logo.webp" alt="BajoCeroWear Logo"
                 width="70" height="70" class="d-inline-block align-text-top rounded-circle" />
         </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
             aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="mainNavbar">

             <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-4">
                 <li class="nav-item">
                    <a class="nav-link fs-5 p-0 <?= $this->currentController === 'home' ? 'active' : '' ?> " href="<?= APP_URL ?>/home">Inicio</a>
                 </li>
                 <li class="nav-item">
                    
                     <a class="nav-link fs-5 p-0 <?= $this->currentController === 'producto' ? 'active' : '' ?> " href="<?= APP_URL ?>/producto">Productos</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link fs-5 p-0" href="sale/index">Ventas</a>
                 </li>
                 <li class="nav-item">
                    <a class="nav-link fs-5 p-0 <?= $this->currentController === 'usuario' ? 'active' : '' ?> " href="<?= APP_URL ?>/usuario">Usuarios</a>
                 </li>
             </ul>

             <ul class="navbar-nav ms-auto mb-2 mb-lg-0 p-0">
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle fs-5 p-0" href="#" role="button" data-bs-toggle="dropdown"
                         aria-expanded="false">
                         Mi cuenta
                     </a>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li class="p-1">
                             <a class="dropdown-item p-1 d-flex justify-content-between"
                                 href="javascript:void(0)">Mis datos <i class="bi bi-person-circle"></i></a>
                         </li>
                         <li class="p-1">
                             <a class="dropdown-item p-1 d-flex justify-content-between"
                                 href="authentication/index.html">Cerrar sesión <i
                                     class="bi bi-box-arrow-in-right"></i></a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </div>
     </div>
 </nav>