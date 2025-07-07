<?php

namespace app\libs\pipeline\middlewares;

use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

use app\core\controllers\ErrorController;


final class RouterHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware{
    
    public function __construct(){
        parent::__construct();
    }

    public function handler(Request $request, Response $response): void{
        $controller = ucfirst($request->getController()) . "Controller";
        $controller = "app\\core\\controllers\\" . $controller;
        //app\core\controllers\CategoriaController.php

        if (!class_exists($controller) || !method_exists($controller, $request->getAction())) {
            $this->showNotFoundPage($request->getController(), $request->getAction());
        }

        //Se pre-configura la respuesta
        $response->setController($request->getController());
        $response->setAction($request->getAction());

        //Se invoca el endpoint
        call_user_func_array(
            array(new $controller([],[]), $request->getAction()),
            array($request, $response)
        );
    }

    private function showNotFoundPage(string $controller, string $action): void {
        $errorController = new ErrorController();
        $errorController->notFound($controller, $action);
        exit;
    }
}