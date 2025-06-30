<?php

namespace app\libs\pipeline\middlewares;

use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

final class AuthenticationHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware {

    public function __construct() {
        parent::__construct();
    }

    public function handler(Request $request, Response $response): void {
        session_start();

        $controller = $request->getController();
        $action = $request->getAction();

        // Caso raíz: si controller y action están vacíos (raíz /)
        if (empty($controller) && empty($action)) {
            if (!empty($_SESSION["usuario"])) {
                // Redirigir a home/index
                header("Location: /home/index");
                exit;
            } else {
                // Redirigir a authentication/index (login)
                header("Location: /authentication/index");
                exit;
            }
        }

        // Rutas públicas
        $rutasPublicas = [
            "authentication:login",
            "authentication:logout",
            "authentication:index" // para permitir login sin sesión
        ];

        $rutaActual = strtolower("{$controller}:{$action}");

        if (!in_array($rutaActual, $rutasPublicas)) {
            if (empty($_SESSION["usuario"])) {
                $response->setError("Debe iniciar sesión para acceder a esta funcionalidad");
                $response->setMessage("Acceso no autorizado");
                $response->send();
                return;
            }
        }

        $this->handlerNext($request, $response);
    }

}
