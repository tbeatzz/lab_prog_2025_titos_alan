<?php

require_once '../../app/config/AppConfig.php';
require_once '../../app/config/DBConfig.php';
require_once '../../app/vendor/autoload.php';

use app\libs\database\Connection;
use app\core\models\dto\CategoriaDto;
use app\core\models\dao\CategoriaDao;
try{
    $data = ["id" => 0, "nombre" => "remeras123"];
    $dto = new CategoriaDto($data);

    $dao = new CategoriaDao(Connection::get());
    unset($data["id"]);
    $dao->save($data);
    

    $data = ["id" => 71];
    $dao = new CategoriaDao(Connection::get());
    $result = $dao->load($data["id"]);
    print_r($result);

}
catch(\PDOException $ex){
    echo "Error database => " . $ex->getMessage();
}
catch(\Exception $ex){
    echo "Error sistema => " . $ex->getMessage();
}