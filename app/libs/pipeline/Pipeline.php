<?php

namespace app\libs\pipeline\middlewares;

use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

final class Pipeline{ #completar

    private ?InterfaceMiddleware $first, $last;

    public function __construct(){
        $this->first = $this -> last = null;
    }

    public function pipe(InterfaceMiddleware $middleware){
        if($this->first == null){
            $this->first = $this -> last = $middleware;
        }else{
            $this->last->setNext($middleware);
            $this->last = $middleware;
        }
        return $this;
    }

    public function handler(Request $request, Response $response){
        if($this->first !=null){
            $this->first->handler($request, $response);
        }
    }

}