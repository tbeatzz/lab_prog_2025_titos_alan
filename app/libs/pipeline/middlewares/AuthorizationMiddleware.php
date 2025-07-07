<?php

namespace app\libs\pipeline\middlewares;

use app\libs\http\Request;
use app\libs\http\Response;
use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;

use app\core\controllers\ErrorController;

final class AuthorizationMiddleware extends BaseMiddleware implements InterfaceMiddleware {

    public function __construct() {
        parent::__construct();
    }

    public function handler(Request $request, Response $response): void {

        

        // Si no hay usuario, que lo capture el AuthenticationMiddleware
        if (!isset($_SESSION["perfil"])) {
            $this->handlerNext($request, $response);
            return;
        }

        $perfil = $_SESSION["perfil"];
        $controller = strtolower($request->getController());
        $action = strtolower($request->getAction());

        // ➡️ Excepción: dejar pasar AuthenticationController
        if ($controller === 'authentication') {
            $this->handlerNext($request, $response);
            return;
        }

        if ($perfil === "Administrador") {
            // Admin puede hacer todo
            $this->handlerNext($request, $response);
            return;
        }

        if ($perfil === "Operador") {
            // Operador: solo módulos permitidos
            $modulosPermitidos = ["cliente", "cuenta", "home","producto","categoria"];
            

            if (!in_array($controller, $modulosPermitidos)) {
                $this->showForbiddenPage();
            }

            // En módulo "cuenta" solo puede editar su contraseña y ver su perfil
            if ($controller === "cuenta" && !in_array($action, ["index", "misdatos", "editpassword"])) {
                $this->showForbiddenPage();
            }

            

            $this->handlerNext($request, $response);
            return;
        }

        // Si el perfil no es reconocido
        $this->showForbiddenPage();
    }

    private function showForbiddenPage(): void {
        $errorController = new ErrorController();
        $errorController->forbidden();
        exit;
    }


}
