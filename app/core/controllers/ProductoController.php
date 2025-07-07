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
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

     /**
     * Crea una nueva vista 
     */
    public function edit(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
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
     * Exporta la lista de usuarios a PDF.
     */
    public function exportPdf(Request $request, Response $response): void
    {
        try {
            // Leer filtros desde el request (igual que en list())
            $inputData = $request->getDataFromInput();

             // Filtros desde input JSON o parámetros GET
            $rawFilters = [
                "categoria" => $inputData['categoria'] ?? $request->getParameterValue("categoria", null),
                "nombre"    => $inputData['nombre'] ?? $request->getParameterValue("nombre", null),
                "codigo"    => $inputData['codigo'] ?? $request->getParameterValue("codigo", null),
                "orden"     => $inputData['orden'] ?? $request->getParameterValue("orden", null),
                "limit"     => $inputData['limit'] ?? $request->getParameterValue("limit", null),
                "offset"    => $inputData['offset'] ?? $request->getParameterValue("offset", 0),
            ];

            // 🎯 Mapeo de nombres de filtros del front a los internos del backend
            $filterMap = [
                "categoria" => "categoriaId",
                "nombre"    => "nombre",
                "codigo"    => "codigo",
                "orden"     => "orden",
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
            $service = new ProductoService();
            $productos = $service->list($mappedFilters);

            // Generar PDF
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
     * Exporta los datos de un producto específico a PDF.
     */
    public function exportSinglePdf(Request $request, Response $response): void
    {
        try {
            // Obtener el ID desde la URL
            $id = (int) $request->getParameterValue('id', 0);
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception('ID de producto inválido');
            }

            // Cargar datos del usuario
            $service = new ProductoService();
            $dto = $service->load($id);
            if (!$dto) {
                throw new \Exception('producto no encontrado');
            }

            // Usar PDFService para generar el PDF
            $pdfService = new PDFService();
            $templatePath = APP_DIR_PDF . $request->getController() . '/pdf_single.php';
            $pdfService->generatePdf(
                $templatePath,
                ['producto' => $dto->toArray()],
                "producto_{$id}_" . date('Ymd_His') . ".pdf"
            );
        } catch (\Exception $e) {
            error_log("Error en UsuarioController::exportSinglePdf: " . $e->getMessage());
            $response->setMessage("<p>Error al generar el PDF: {$e->getMessage()}</p>");
            $response->send();
        }
    }

    public function cantidadProductos(Request $request, Response $response): void {
       
        $service = new ProductoService();
        $response->setResult( $service->getCantidadProductos());
        $response->setMessage("Cantidad de productos");
        $response->send();
    }



}
