<?php

namespace app\libs\pipeline\middlewares;

use app\libs\http\Request;
use app\libs\http\Response;
use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\core\controllers\ErrorController;

/**
 * Middleware de autorización.
 * 
 * Valida el perfil del usuario y determina si puede acceder a determinados controladores/acciones.
 * Si no tiene permisos, redirige a la página 403 Forbidden.
 */
final class AuthorizationMiddleware extends BaseMiddleware implements InterfaceMiddleware
{
    /**
     * Constructor que invoca el constructor base.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ejecuta la lógica de autorización.
     * 
     * - Si el usuario es "Administrador", permite todo.
     * - Si es "Operador", permite acceso solo a ciertos controladores/acciones.
     * - Si no tiene permisos, redirige a una página 403.
     *
     * @param Request $request Objeto que representa la solicitud HTTP.
     * @param Response $response Objeto que representa la respuesta HTTP.
     */
    public function handler(Request $request, Response $response): void
    {
        // Si no hay sesión iniciada, lo maneja el AuthenticationMiddleware
        if (!isset($_SESSION["perfil"])) {
            $this->handlerNext($request, $response);
            return;
        }

        $perfil = $_SESSION["perfil"];
        $controller = strtolower($request->getController());
        $action = strtolower($request->getAction());

        // Excepción: dejar pasar el AuthenticationController
        if ($controller === 'authentication') {
            $this->handlerNext($request, $response);
            return;
        }

        // Administrador tiene acceso completo
        if ($perfil === "Administrador") {
            $this->handlerNext($request, $response);
            return;
        }

        // Operador tiene acceso restringido
        if ($perfil === "Operador") {
            $modulosPermitidos = ["cliente", "cuenta", "home", "producto", "categoria"];

            // Solo puede acceder a los módulos permitidos
            if (!in_array($controller, $modulosPermitidos)) {
                $this->showForbiddenPage();
            }

            // Dentro del módulo "cuenta", solo puede hacer acciones específicas
            if ($controller === "cuenta" && !in_array($action, ["index", "misdatos", "editpassword"])) {
                $this->showForbiddenPage();
            }

            $this->handlerNext($request, $response);
            return;
        }

        // Si el perfil no es reconocido, acceso denegado
        $this->showForbiddenPage();
    }

    /**
     * Muestra la página de error 403 (Forbidden) y finaliza la ejecución.
     */
    private function showForbiddenPage(): void
    {
        $errorController = new ErrorController();
        $errorController->forbidden();
        exit;
    }
}
