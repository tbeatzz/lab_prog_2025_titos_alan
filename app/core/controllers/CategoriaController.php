<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\CategoriaService;
use app\core\models\dto\CategoriaDto;

/**
 * Controlador del módulo Categoría.
 * Gestiona las peticiones HTTP y comunica la vista con la lógica de negocio (service).
 */
final class CategoriaController extends BaseController implements InterfaceController {

    /**
     * Muestra la vista principal del módulo Categoría.
     *
     * @param Request $request
     * @param Response $response
     */
    public function index(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/categoria/index.js");
        echo "<h1>Funciona el controlador de Categoría</h1>";
        $this->setCurrentView($request);
        require_once APP_FILE_TEMPLATE;
    }

    public function edit(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/categoria/edit.js");
        $this->setCurrentView($request);
        require_once APP_FILE_TEMPLATE;
    }

    /**
     * Carga una categoría por ID.
     * Ejemplo: GET /categoria/load/1
     *
     * @param Request $request
     * @param Response $response
     */
    public function load(Request $request, Response $response): void {
        $id = (int) $request->getParameterValue("id", 0);
        $service = new CategoriaService();
        $dto = $service->load($id);

        $response->setResult($dto->toArray());
        $response->send();
    }

    

    /**
     * Invoca la vista para crear una nueva categoría.
     *
     * @param Request $request
     * @param Response $response
     */
    public function create(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/categoria/create.js");
        $this->setCurrentView($request);
        require_once APP_FILE_TEMPLATE;
    }

    /**
     * Guarda una nueva categoría.
     * Ejemplo: POST /categoria/save con body JSON {"nombre": "Guantes"}
     *
     * @param Request $request
     * @param Response $response
     */
    public function save(Request $request, Response $response): void {
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->save($dto);

        $response->setMessage("<p>Se agregó una nueva categoría al sistema</p>");
        $response->send();
    }

    /**
     * Actualiza una categoría existente.
     * Ejemplo: PUT /categoria/update con body JSON {"id":1, "nombre": "Nuevo nombre"}
     *
     * @param Request $request
     * @param Response $response
     */
    public function update(Request $request, Response $response): void {
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->update($dto);

        $response->setMessage("<p>Se modificó la categoría correctamente</p>");
        $response->send();
    }

    /**
     * Elimina una categoría.
     * Ejemplo: DELETE /categoria/delete con body JSON {"id":1}
     *
     * @param Request $request
     * @param Response $response
     */
    public function delete(Request $request, Response $response): void {
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->delete($dto);

        $response->setMessage("<p>Se eliminó la categoría correctamente</p>");
        $response->send();
    }

    /**
     * Lista categorías con filtros opcionales.
     * Ejemplo: GET /categoria/list?nombre=guante&limit=10&offset=0
     *
     * @param Request $request
     * @param Response $response
     */
    public function list(Request $request, Response $response): void {
        $filters = [
            "nombre" => $request->getParameterValue("nombre", null),
            "limit"  => $request->getParameterValue("limit", null),
            "offset" => $request->getParameterValue("offset", null)
        ];

        $service = new CategoriaService();
        $categorias = $service->list($filters);

        $response->setResult($categorias);
        $response->send();
    }
}
