<?php
    // print_r ($_GET);

    require_once "../app/config/AppConfig.php";
    require_once "../app/config/DBConfig.php";
    require_once "../app/vendor/autoload.php";


    
    // enrutador
    use app\App;

    App::run();
?>