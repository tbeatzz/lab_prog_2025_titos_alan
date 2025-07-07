<?php

namespace app\core\controllers;

use app\core\controllers\base\BaseController;

/**
 * Controlador para manejar errores del sistema.
 * Permite mostrar vistas personalizadas para errores 403 (prohibido) y 404 (no encontrado).
 */
final class ErrorController extends BaseController {

    /**
     * Muestra una vista personalizada para errores de acceso denegado (HTTP 403).
     * Se utiliza cuando un usuario intenta acceder a una sección sin permisos.
     *
     * @return void
     */
    public function forbidden(): void {
        // Establece el código de estado HTTP 403 (Prohibido)
        http_response_code(403);

        // Renderiza una vista de error personalizada
        $this->renderError('403', [
            'title' => 'Acceso denegado',
            'message' => 'No tenés permiso para acceder a esta sección.',
        ]);
    }

    /**
     * Muestra una vista personalizada para errores de página no encontrada (HTTP 404).
     * Se utiliza cuando la ruta o recurso solicitado no existe.
     *
     * @return void
     */
    public function notFound(): void {
        // Establece el código de estado HTTP 404 (No encontrado)
        http_response_code(404);

        // Renderiza una vista de error personalizada
        $this->renderError('404', [
            'title' => 'Página no encontrada',
            'message' => 'La página solicitada no existe.',
        ]);
    }
}
