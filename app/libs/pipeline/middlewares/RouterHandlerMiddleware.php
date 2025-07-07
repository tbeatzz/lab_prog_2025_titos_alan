<?php

namespace app\libs\pipeline\middlewares;

use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\ErrorController;

/**
 * Middleware responsable de resolver e invocar el controlador y acción solicitados.
 * 
 * Si el controlador o la acción no existen, muestra una página 404.
 */
final class RouterHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware
{
    /**
     * Constructor que invoca el constructor base.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ejecuta la lógica de resolución del controlador y acción.
     * 
     * - Verifica que el controlador y la acción existan.
     * - Si existen, los ejecuta.
     * - Si no existen, muestra la página de error 404.
     *
     * @param Request $request Objeto que representa la solicitud HTTP.
     * @param Response $response Objeto que representa la respuesta HTTP.
     */
    public function handler(Request $request, Response $response): void
    {
        // Construye el nombre completo del controlador (con namespace)
        $controller = ucfirst($request->getController()) . "Controller";
        $controller = "app\\core\\controllers\\" . $controller;

        // Verifica si el controlador y el método existen
        if (!class_exists($controller) || !method_exists($controller, $request->getAction())) {
            $this->showNotFoundPage($request->getController(), $request->getAction());
        }

        // Configura el controlador y acción en la respuesta
        $response->setController($request->getController());
        $response->setAction($request->getAction());

        // Ejecuta el método del controlador pasando el request y el response
        call_user_func_array(
            [new $controller([], []), $request->getAction()],
            [$request, $response]
        );
    }

    /**
     * Muestra la página 404 (Not Found) y detiene la ejecución.
     *
     * @param string $controller Nombre del controlador solicitado.
     * @param string $action Nombre de la acción solicitada.
     */
    private function showNotFoundPage(string $controller, string $action): void
    {
        $errorController = new ErrorController();
        $errorController->notFound($controller, $action);
        exit;
    }
}
