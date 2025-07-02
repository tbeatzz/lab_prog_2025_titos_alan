<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\ProductoService;
use app\core\models\dto\ProductoDto;

/**
 * Controlador para gestionar productos.
 * Conecta las solicitudes HTTP con la lógica de servicio.
 */
final class ProductoController extends BaseController implements InterfaceController {

    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Carga un producto por ID.
     */
    public function load(Request $request, Response $response): void {
        $id = (int)$request->getParameterValue("id", 0);
        $service = new ProductoService();
        $dto = $service->load($id);
        $response->setResult($dto->toArray());
        $response->send();
    }


    /**
     * Crea una nueva vista para el formulario de productos (opcional).
     */
    public function create(Request $request, Response $response): void {
        $this->scripts[] = "app/js/producto/create.js";
        $this->render($request);
    }


    /**
     * Guarda un nuevo producto.
     */
    public function save(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->save($dto);
        $response->setMessage("Producto agregado correctamente.");
        $response->send();
    }

    /**
     * Actualiza un producto existente.
     */
    public function update(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->update($dto);
        $response->setMessage("Producto actualizado correctamente.");
        $response->send();
    }

    /**
     * Elimina un producto existente.
     */
    public function delete(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->delete($dto);
        $response->setMessage("Producto eliminado correctamente.");
        $response->send();
    }

    /**
     * Lista productos con filtros opcionales.
     */
    public function list(Request $request, Response $response): void {
        $filters = [
            "nombre"      => $request->getParameterValue("nombre", null),
            "categoriaId" => $request->getParameterValue("categoriaId", null),
            "limit"       => $request->getParameterValue("limit", null),
            "offset"      => $request->getParameterValue("offset", null),
        ];

        $service = new ProductoService();
        $productos = $service->list($filters);
        $response->setResult($productos);
        $response->send();
    }
}
