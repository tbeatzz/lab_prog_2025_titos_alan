<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        require_once APP_DIR_TEMPLATE . 'includes/head.php';

        if(isset($this->scripts) && is_array($this->scripts)){
            foreach($this->scripts as $script){
               echo '<script type="module" defer src="' . APP_URL . '/' . $script . '"></script>';
            }
        }
    ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
        require_once APP_DIR_TEMPLATE . "includes/menu.php";
    ?>
   
   <main class="flex-grow-1">
        <div class="container py-5">
            <?php 
                require_once APP_DIR_TEMPLATE . "includes/breadcrumbs.php"; 
                require_once APP_DIR_VIEWS . $this->view;
            ?>
        </div>
   </main>

    <footer class="footer py-4 mt-auto">
        <?php
            require_once APP_DIR_TEMPLATE . "includes/footer.php";
        ?>
    </footer>
</body>
</html>