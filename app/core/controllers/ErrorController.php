<?php

namespace app\core\controllers;


use app\core\controllers\base\BaseController;

final class ErrorController extends BaseController {

    public function forbidden(): void {
        http_response_code(403);
        $this->renderError('403', [
            'title' => 'Acceso denegado',
            'message' => 'No tenés permiso para acceder a esta sección.',
        ]);
    }

    public function notFound(): void {
        http_response_code(404);
        $this->renderError('404', [
            'title' => 'Página no encontrada',
            'message' => 'La página solicitada no existe.',
        ]);
    }
}
