<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\CategoriaService;
use app\core\models\dto\CategoriaDto;
use app\core\services\PDFService;

/**
 * Clase CategoriaController
 *
 * Controlador encargado de gestionar las operaciones CRUD para categorías,
 * así como la exportación de datos en formato PDF.
 *
 * Implementa la interfaz InterfaceController y extiende BaseController.
 *
 * @package app\core\controllers
 */
final class CategoriaController extends BaseController implements InterfaceController {

    /**
     * Muestra la vista principal del módulo Categoría.
     *
     * @param Request $request  Solicitud HTTP con información del cliente.
     * @param Response $response Respuesta HTTP a enviar al cliente.
     * @return void
     */
    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Muestra la vista de edición de categoría.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function edit(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Carga una categoría específica por su ID.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function load(Request $request, Response $response): void {
        $id = (int) $request->getParameterValue("id", 0);
        $service = new CategoriaService();
        $dto = $service->load($id);

        $response->setResult($dto->toArray());
        $response->send();
    }

    /**
     * Muestra la vista para crear una nueva categoría.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function create(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Guarda una nueva categoría a partir de los datos recibidos.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function save(Request $request, Response $response): void {
        $dto = new CategoriaDto($request->getDataFromInput());
        $service = new CategoriaService();
        $service->save($dto);

        $response->setMessage("<p>Se agregó una nueva categoría al sistema</p>");
        $response->send();
    }

    /**
     * Actualiza una categoría existente con nuevos datos.
     *
     * @param Request $request
     * @param Response $response
     * @return void
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
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function delete(Request $request, Response $response): void {
        try {
            $dto = new CategoriaDto($request->getDataFromInput());
            $service = new CategoriaService();
            $service->delete($dto);
            $response->setMessage("<p>Se eliminó la categoría correctamente</p>");
        } catch (\Exception $e) {
            $response->setStatus(false);
            $response->setMessage($e->getMessage());
        }

        $response->send();
    }

    /**
     * Lista categorías con filtros opcionales como nombre, límite y desplazamiento.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function list(Request $request, Response $response): void {
        $inputData = $request->getDataFromInput();

        $filters = [
            "nombre" => $inputData['nombre'] ?? $request->getParameterValue("nombre", null),
            "limit"  => $inputData['limit'] ?? $request->getParameterValue("limit", null),
            "offset" => $inputData['offset'] ?? $request->getParameterValue("offset", null)
        ];

        $service = new CategoriaService();
        $categorias = $service->list($filters);

        $response->setResult($categorias);
        $response->send();
    }

    /**
     * Exporta a PDF la lista de categorías filtradas.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function exportPdf(Request $request, Response $response): void {
        try {
            $inputData = $request->getDataFromInput();

            $rawFilters = [
                "nombre" => $inputData['nombre'] ?? $request->getParameterValue("nombre", null),
                "limit"  => $inputData['limit'] ?? $request->getParameterValue("limit", null),
                "offset" => $inputData['offset'] ?? $request->getParameterValue("offset", 0),
            ];

            $filterMap = [
                "nombre" => "nombre",
                "limit"  => "limit",
                "offset" => "offset",
            ];

            $mappedFilters = [];
            foreach ($rawFilters as $key => $value) {
                if ($value !== null && $value !== '') {
                    $internalKey = $filterMap[$key] ?? $key;
                    $mappedFilters[$internalKey] = $value;
                }
            }

            $service = new CategoriaService();
            $categorias = $service->list($mappedFilters);

            $pdfService = new PDFService();
            $templatePath = APP_DIR_PDF . $request->getController() . '/pdf.php';
            $pdfService->generatePdf(
                $templatePath,
                ['categorias' => $categorias],
                "categoria_" . date('Ymd_His') . ".pdf"
            );
        } catch (\Exception $e) {
            error_log("Error en categoriaController::exportPdf: " . $e->getMessage());
            $response->setMessage("<p>Error al generar el PDF: {$e->getMessage()}</p>");
            $response->send();
        }
    }

    /**
     * Exporta a PDF los datos de una categoría específica.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function exportSinglePdf(Request $request, Response $response): void {
        try {
            $id = (int) $request->getParameterValue('id', 0);
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception('ID de categoría inválido');
            }

            $service = new CategoriaService();
            $dto = $service->load($id);
            if (!$dto) {
                throw new \Exception('Categoría no encontrada');
            }

            $pdfService = new PDFService();
            $templatePath = APP_DIR_PDF . $request->getController() . '/pdf_single.php';
            $pdfService->generatePdf(
                $templatePath,
                ['categoria' => $dto->toArray()],
                "categoria_{$id}_" . date('Ymd_His') . ".pdf"
            );
        } catch (\Exception $e) {
            error_log("Error en categoriaController::exportSinglePdf: " . $e->getMessage());
            $response->setMessage("<p>Error al generar el PDF: {$e->getMessage()}</p>");
            $response->send();
        }
    }

    /**
     * Obtiene la cantidad total de categorías registradas en el sistema.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function cantidadCategorias(Request $request, Response $response): void {
        $service = new CategoriaService();
        $response->setResult($service->getCantidadCategorias());
        $response->setMessage("Cantidad de categorías");
        $response->send();
    }
}
