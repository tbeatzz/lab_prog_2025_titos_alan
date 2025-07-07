<?php
namespace app\core\services;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Servicio para generar documentos PDF.
 */
class PDFService
{
    /**
     * Genera un PDF a partir de una plantilla y datos.
     *
     * @param string $templatePath Ruta absoluta al archivo de plantilla
     * @param array $data Datos para la plantilla
     * @param string $filename Nombre del archivo PDF
     * @param string $paper Tamaño del papel (default: A4)
     * @param string $orientation Orientación (default: portrait)
     * @return void
     * @throws \Exception Si la plantilla no se encuentra o hay un error en la generación del PDF
     */
    public function generatePdf(string $templatePath, array $data, string $filename, string $paper = 'A4', string $orientation = 'portrait'): void
    {
        try {
            // Verificar que la plantilla exista
            if (!file_exists($templatePath)) {
                throw new \Exception("La plantilla no se encuentra en: $templatePath");
            }

            // Cargar la plantilla
            ob_start();
            extract($data); 
            require_once $templatePath;
            $html = ob_get_clean();

            // Configurar opciones de DOMPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true); // Soporte para HTML5
            $options->set('isRemoteEnabled', true); // Permitir recursos remotos (si usas imágenes)
            $options->set('defaultFont', 'Arial'); // Fuente predeterminada

            // Crear instancia de DOMPDF
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper($paper, $orientation);
            $dompdf->render();

            // Enviar el PDF
            $dompdf->stream($filename, ["Attachment" => true]);
            exit;
        } catch (\Exception $e) {
            error_log("Error en PDFService::generatePdf: " . $e->getMessage());
            throw $e;
        }
    }
}