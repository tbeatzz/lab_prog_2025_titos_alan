<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\ProductoService;
use app\core\models\dto\ProductoDto;

final class ProductoController extends BaseController implements InterfaceController {

    public function index(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/producto/index.js");
        echo "<h1>Funciona el controlador de Producto</h1>";
        // $this->setCurrentView($request);
        // require_once APP_FILE_TEMPLATE;
    }

    public function load(Request $request, Response $response): void {
        $service = new ProductoService();
        $dto = $service->load((int) $request->getId());
        $response->setResult($dto->toArray());
        $response->send();
    }

    public function create(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/producto/create.js");
        // require_once APP_FILE_TEMPLATE;
    }

    public function save(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->save($dto);

        $response->setMessage("<p>Se agregó un nuevo producto al sistema</p>");
        $response->send();
    }

    public function update(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->update($dto);

        $response->setMessage("<p>Se modificó el producto correctamente</p>");
        $response->send();
    }

    public function delete(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->delete($dto);

        $response->setMessage("<p>Se eliminó el producto correctamente</p>");
        $response->send();
    }

    public function list(Request $request, Response $response): void {
        $filters = [
            "nombre" => $request->getParameterValue("nombre", null),
            "limit"  => $request->getParameterValue("limit", null)
        ];

        $service = new ProductoService();
        $productos = $service->list($filters);

        $response->setResult($productos);
        $response->send();
    }
}
