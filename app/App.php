<?php
namespace app;

use app\libs\pipeline\Pipeline;
use app\libs\pipeline\middlewares\ExceptionHandlerMiddleware;
use app\libs\pipeline\middlewares\RouterHandlerMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

final class App{

    private function __construct(){}

    public static function run(){
        $pipeline = new Pipeline();
        // Esto es encadenamiento de metodos, gracias al return $this del metodo pipe
        $pipeline->pipe(new ExceptionHandlerMiddleware())
        ->pipe(new RouterHandlerMiddleware());

        $pipeline->process(new Request(), new Response());
    }
}