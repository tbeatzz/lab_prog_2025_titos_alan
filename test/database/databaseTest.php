<?php
require_once "../../app/config/DBConfig.php";
require_once "../../app/libs/database/Connection.php";
require_once '../../app/core/models/dto/base/InterfaceDto.php';
require_once '../../app/core/models/dto/CategoriaDto.php';

use app\libs\database\Connection;
use app\core\models\dto\CategoriaDto;
    try{
        $conn = Connection::get();
        
        $id = 3;
        $sql = "SELECT id, nombre FROM categorias WHERE id = " . $id;
        $stmt = $conn->query($sql);

        $dto = new CategoriaDto($stmt->fetch(\PDO::FETCH_ASSOC));
        print_r($dto->toArray());
    }
    catch(\PDOException $ex){
        echo "Error database => " . $ex->getMessage();
    }