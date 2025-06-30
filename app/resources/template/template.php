<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        require_once APP_DIR_TEMPLATE . 'includes/head.php';

        if(isset($this->scripts) && is_array($this->scripts)){
            foreach($this->scripts as $script){
                echo '<script defer srce="' . APP_URL . $script .'"></script>';
            }
        }
    ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
        require_once APP_DIR_TEMPLATE . "includes/menu.php";
    ?>
   
   <main>
        <?php
            require_once APP_DIR_VIEWS . $this->view;
        ?>
   </main>

    <footer>
        <?php
            require_once APP_DIR_TEMPLATE . "includes/footer.php";
        ?>
    </footer>
</body>
</html>