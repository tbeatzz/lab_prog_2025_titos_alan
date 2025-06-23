<?php

require_once '../../app/config/AppConfig.php';
require_once '../../app/config/DBConfig.php';
require_once '../../app/vendor/autoload.php';

use app\core\models\dto\CategoriaDto;
use app\core\services\CategoriaService;

try{
    $data = ["id" => 0, "nombre" => "guantes"];
    $dto = new CategoriaDto($data);
    $service = new CategoriaService();
    $service->save($dto);
    echo "<p>Se agregó una nueva categoría al sistema.</p>";
}
catch(\PDOException $ex){
    echo "Error database => " . $ex->getMessage();
}
catch(\Exception $ex){
    echo "Error sistema => " . $ex->getMessage();
}