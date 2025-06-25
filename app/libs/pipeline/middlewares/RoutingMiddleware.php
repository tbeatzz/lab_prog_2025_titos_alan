<?php

namespace app\libs\pipeline\middlewares;

use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

final class RoutingMiddleware extends BaseMiddleware implements InterfaceMiddleware{

    public function __construct(){
        parent::__construct();
    }

    public function handler(Request $request, Response $response):void{
        $controller = ucfirst($request->getController()) . "Controller";
        $controller = "app\\core\\controllers\\" . $controller;

        if(!class_exists($controller) || !method_exists($controller, $request->getAction())){
            throw new \Exception("Controlador y accion incorrectos({$request->getController()}) 
            => {$request -> getAction()})");
        }

        # se preconfigura la rta
        $response->setController($request->getController());
        $response->setAction($request->getAction());

        #se invoca el endpoint

        call_user_func_array(
            array(new $controller, $request -> getAction()),
            array($request,$response)
        );
    }

}