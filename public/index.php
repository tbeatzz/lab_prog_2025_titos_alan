<?php
    // print_r ($_GET);

    require_once "../app/config/AppConfig.php";
    
    // enrutador
    $controller = $_GET['controller'] ?? APP_DEFAULT_CONTROLLER;
    $action = $_GET['action'] ?? APP_DEFAULT_ACTION;
    $base = "../app/resources/views/" . $controller . "/" . $action. ".php";
    require_once $base;


?>