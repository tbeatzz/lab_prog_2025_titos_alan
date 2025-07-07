<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\ProductoService;
use app\core\models\dto\ProductoDto;
use app\core\services\PDFService;

/**
 * Controlador para gestionar productos del sistema.
 * Implementa operaciones CRUD, filtrado, conteo y exportación a PDF.
 */
final class ProductoController extends BaseController implements InterfaceController {

    /**
     * Carga la vista principal del módulo productos.
     */
    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Carga un producto específico por su ID.
     *
     * @param Request $request
     * @param Response $response
     */
    public function load(Request $request, Response $response): void {
        $id = (int)$request->getParameterValue("id", 0);
        $service = new ProductoService();
        $dto = $service->load($id);
        $response->setResult($dto->toArray());
        $response->send();
    }

    /**
     * Renderiza el formulario de creación de productos.
     */
    public function create(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Renderiza el formulario de edición de productos.
     */
    public function edit(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Guarda un nuevo producto.
     *
     * @param Request $request
     * @param Response $response
     */
    public function save(Request $request, Response $response): void {
        try {
            $dto = new ProductoDto($request->getDataFromInput());
            $service = new ProductoService();
            $service->save($dto);

            $response->setMessage("Producto agregado correctamente.");
            $response->send();

        } catch (\Exception $e) {
            http_response_code(400);
            $response->setError($e->getMessage());
            $response->send();
        }
    }

    /**
     * Actualiza los datos de un producto existente.
     */
    public function update(Request $request, Response $response): void {
        $dto = new ProductoDto($request->getDataFromInput());
        $service = new ProductoService();
        $service->update($dto);

        $response->setMessage("Producto actualizado correctamente.");
        $response->send();
    }

    /**
     * Elimina un producto a partir de su ID.
     */
    public function delete(Request $request, Response $response): void {
        $service = new ProductoService();
        $dto = $service->load((int)$request->getId());
        $service->delete($dto);

        $response->setMessage("Se eliminó el producto correctamente");
        $response->send();
    }

    /**
     * Lista productos aplicando filtros opcionales (nombre, código, categoría, etc.).
     */
    public function list(Request $request, Response $response): void {
        $inputData = $request->getDataFromInput();

        $rawFilters = [
            "categoria" => $inputData['categoria'] ?? $request->getParameterValue("categoria", null),
            "nombre"    => $inputData['nombre'] ?? $request->getParameterValue("nombre", null),
            "codigo"    => $inputData['codigo'] ?? $request->getParameterValue("codigo", null),
            "orden"     => $inputData['orden'] ?? $request->getParameterValue("orden", null),
            "limit"     => $inputData['limit'] ?? $request->getParameterValue("limit", null),
            "offset"    => $inputData['offset'] ?? $request->getParameterValue("offset", 0),
        ];

        $filterMap = [
            "categoria" => "categoriaId",
            "nombre"    => "nombre",
            "codigo"    => "codigo",
            "orden"     => "orden",
            "limit"     => "limit",
            "offset"    => "offset",
        ];

        $mappedFilters = [];
        foreach ($rawFilters as $key => $value) {
            if ($value !== null && $value !== '') {
                $internalKey = $filterMap[$key] ?? $key;
                $mappedFilters[$internalKey] = $value;
            }
        }

        $service = new ProductoService();
        $productos = $service->list($mappedFilters);

        $response->setResult($productos);
        $response->send();
    }

    /**
     * Exporta la lista de productos filtrados a un archivo PDF.
     */
    public function exportPdf(Request $request, Response $response): void {
        try {
            $inputData = $request->getDataFromInput();

            $rawFilters = [
                "categoria" => $inputData['categoria'] ?? $request->getParameterValue("categoria", null),
                "nombre"    => $inputData['nombre'] ?? $request->getParameterValue("nombre", null),
                "codigo"    => $inputData['codigo'] ?? $request->getParameterValue("codigo", null),
                "orden"     => $inputData['orden'] ?? $request->getParameterValue("orden", null),
            ];

            $filterMap = [
                "categoria" => "categoriaId",
                "nombre"    => "nombre",
                "codigo"    => "codigo",
                "orden"     => "orden",
            ];

            $mappedFilters = [];
            foreach ($rawFilters as $key => $value) {
                if ($value !== null && $value !== '') {
                    $internalKey = $filterMap[$key] ?? $key;
                    $mappedFilters[$internalKey] = $value;
                }
            }

            $service = new ProductoService();
            $productos = $service->list($mappedFilters);

            $pdfService = new PDFService();
            $templatePath = APP_DIR_PDF . $request->getController() . '/pdf.php';
            $pdfService->generatePdf(
                $templatePath,
                ['productos' => $productos],
                "productos_" . date('Ymd_His') . ".pdf"
            );

        } catch (\Exception $e) {
            error_log("Error en ProductoController::exportPdf: " . $e->getMessage());
            $response->setMessage("<p>Error al generar el PDF: {$e->getMessage()}</p>");
            $response->send();
        }
    }

    /**
     * Exporta un producto específico a PDF por su ID.
     */
    public function exportSinglePdf(Request $request, Response $response): void {
        try {
            $id = (int) $request->getParameterValue('id', 0);
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception('ID de producto inválido');
            }

            $service = new ProductoService();
            $dto = $service->load($id);
            if (!$dto) {
                throw new \Exception('Producto no encontrado');
            }

            $pdfService = new PDFService();
            $templatePath = APP_DIR_PDF . $request->getController() . '/pdf_single.php';
            $pdfService->generatePdf(
                $templatePath,
                ['producto' => $dto->toArray()],
                "producto_{$id}_" . date('Ymd_His') . ".pdf"
            );

        } catch (\Exception $e) {
            error_log("Error en ProductoController::exportSinglePdf: " . $e->getMessage());
            $response->setMessage("<p>Error al generar el PDF: {$e->getMessage()}</p>");
            $response->send();
        }
    }

    /**
     * Devuelve la cantidad total de productos registrados en el sistema.
     */
    public function cantidadProductos(Request $request, Response $response): void {
        $service = new ProductoService();
        $response->setResult($service->getCantidadProductos());
        $response->setMessage("Cantidad de productos");
        $response->send();
    }

}
