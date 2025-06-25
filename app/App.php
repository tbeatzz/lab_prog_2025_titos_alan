<?php

namespace app;

use app\libs\pipeline\middlewares\Pipeline;
use app\libs\pipeline\middlewares\ExceptionHandlerMiddleware;
use app\libs\pipeline\middlewares\RoutingMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

final class App{

    private function __construct(){

    }

    public static function run(){
        $pipeline = new Pipeline();

        $pipeline -> pipe(new ExceptionHandlerMiddleware()) 
        ->pipe(new RoutingMiddleware);
        
        $pipeline -> handler(new Request(), new Response());
    }

}