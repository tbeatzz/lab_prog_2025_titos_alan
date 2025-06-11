<?php
    require_once "../../app/config/DBConfig.php"
    require_once "../../app/libs/database/Connection.php"

    require_once '../../app/core/models/dto/base/InterfaceDto.php';
    require_once '../../app/core/models/dto/CategoriaDto.php';

    use app\libs\database\Connection;
    use app\core\models\dto\CategoriaDto;


    try{
        $conn = Connection::get();

        $id = 3;
        // $sql = "INSERT INTO categorias VALUES(DEFAULT, 'Bolsos')";
        $sql = "SELECT id, nombre FROM categorias WHERE id = ".$id;
        // $filasAfectadas =   $conn -> exec($sql);
        $stmt = $conn -> query($sql);
        $dto = newCategoriaDto($stmt->fetch(\PDO::FETCH_ASSOC));
        
        print_r($dto->toArray());

        // echo "<p> reg encontrados => {$stmt->rowCount()} </p>"

        // $reg = $stmt->fetch();

        // echo "primera categoria -> {$reg->nombre}"



    }catch(\PDOException $ex){
        echo "error => " . $ex -> getMessage();
    }