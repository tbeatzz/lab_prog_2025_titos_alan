<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\CategoriaService;
use app\core\models\dto\CategoriaDto;

final class CategoriaController extends BaseController implements InterfaceController{

    public function index(Request $request, Response $response): void{
        //HAGO UN PUSH DE LOS SCRIPTS QUE TIENE QUE CARGAR LA PLANTILLA
        array_push($this->scripts, "app/js/categoria/index.js");
        echo "<h1>Funciona el controlador de Categoría</h1>";
        //$this->setCurrentView($request);
        // require_once APP_FILE_TEMPLATE;
    }


    public function load(Request $request, Response $response): void {
        $id = (int) $request->getParameterValue("id", 0); 
        $service = new CategoriaService();
        $dto = $service->load($id);
        $response->setResult($dto->toArray());
        $response->send();
    }


    public function create(Request $request, Response $response):void{
        array_push($this->scripts, "app/js/categoria/create.js");
        // require_once APP_FILE_TEMPLATE;
    }

    public function save(Request $request, Response $response): void{
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->save($dto);
    
        $response->setMessage("<p>Se agregó una nueva categoría al sistema</p>");
        $response->send();
    }

    public function update(Request $request, Response $response): void{
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->update($dto);

        $response->setMessage("<p>Se modificó la categoría correctamente</p>");
        $response->send();
    }


    public function delete(Request $request, Response $response): void{
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->delete($dto);

        $response->setMessage("<p>Se eliminó la categoría correctamente</p>");
        $response->send();
    }


    public function list(Request $request, Response $response): void{
        $filters = [
            "estado" => $request->getParameterValue("estado", null),
            "limit"  => $request->getParameterValue("limit", null)
        ];

        $service = new CategoriaService();
        $categorias = $service->list($filters);

        $response->setResult($categorias);
        $response->send();
    }

}