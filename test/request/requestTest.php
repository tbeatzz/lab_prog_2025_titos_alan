<?php


require_once '../../app/config/AppConfig.php';
require_once '../../app/config/DBConfig.php';
require_once '../../app/vendor/autoload.php';

use app\core\models\dto\CategoriaDto;
use app\core\services\CategoriaService;
use app\libs\http\Request;
use app\libs\http\Response;

$response = new Response();

try{
    $request = new Request();
    $response->setController($request->getController());
    $response->setAction($request->getAction());

    $dto = new CategoriaDto($request->getDataFromInput());
    $service = new CategoriaService();
    $service->save($dto);
    
    $response->setMessage("<p>Se agregó una nueva categoría al sistema.</p>");
    $response->send();
}
catch(\PDOException $ex){
    $response->setError("Error database => " . $ex->getMessage());
    $response->send();
}
catch(\Exception $ex){
    $response->setError("Error sistema => " . $ex->getMessage());
    $response->send();
}