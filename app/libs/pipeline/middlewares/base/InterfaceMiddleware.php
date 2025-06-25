<?php

namespace app\libs\pipeline\middlewares\base;

use app\libs\http\Request;
use app\libs\http\Response;


interface InterfaceMiddleware{

    public function handler(Request $request, Response $response):void;
    
    public function setNext(InterfaceMiddleware $middleware):void;

    public function handlerNext(Request $request, Response $response):void;


}