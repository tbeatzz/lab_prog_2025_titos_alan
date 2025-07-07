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
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    public function edit(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
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
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
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
        try {
            $dto = new CategoriaDto($request->getDataFromInput());
            $service = new CategoriaService();
            $service->delete($dto);
            $response->setMessage("<p>Se eliminó la categoría correctamente</p>");
        } catch (\Exception $e) {
            // Enviar mensaje de error
            $response->setStatus(false);
            $response->setMessage($e->getMessage());
        }

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
        // Obtener filtros del cuerpo JSON o de parámetros GET
        $inputData = $request->getDataFromInput();  // ← Esto toma los datos del POST JSON
        
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
     * Exporta la lista de usuarios a PDF.
     */
    public function exportPdf(Request $request, Response $response): void
    {
        try {
            // Leer filtros desde el request (igual que en list())
            $inputData = $request->getDataFromInput();

             // Filtros desde input JSON o parámetros GET
            $rawFilters = [
                "nombre"    => $inputData['nombre'] ?? $request->getParameterValue("nombre", null),
                "limit"     => $inputData['limit'] ?? $request->getParameterValue("limit", null),
                "offset"    => $inputData['offset'] ?? $request->getParameterValue("offset", 0),
            ];

            // 🎯 Mapeo de nombres de filtros del front a los internos del backend
            $filterMap = [
                "nombre"    => "nombre",
                "limit"     => "limit",
                "offset"    => "offset",
            ];

            // 🧩 Aplicar el mapeo
            $mappedFilters = [];
            foreach ($rawFilters as $key => $value) {
                if ($value !== null && $value !== '') {
                    $internalKey = $filterMap[$key] ?? $key;
                    $mappedFilters[$internalKey] = $value;
                }
            }


            // Obtener la lista filtrada
            $service = new CategoriaService();
            $categorias = $service->list($mappedFilters);

            // Generar PDF
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
     * Exporta los datos de un producto específico a PDF.
     */
    public function exportSinglePdf(Request $request, Response $response): void
    {
        try {
            // Obtener el ID desde la URL
            $id = (int) $request->getParameterValue('id', 0);
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception('ID de categoria inválido');
            }

            // Cargar datos del usuario
            $service = new CategoriaService();
            $dto = $service->load($id);
            if (!$dto) {
                throw new \Exception('categoria no encontrada');
            }

            // Usar PDFService para generar el PDF
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
    public function cantidadCategorias(Request $request, Response $response): void {
       
        $service = new CategoriaService();
        $response->setResult( $service->getCantidadCategorias());
        $response->setMessage("Cantidad de categorias");
        $response->send();
    }

}
