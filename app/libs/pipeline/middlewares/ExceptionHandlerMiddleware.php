<?php

namespace app\libs\pipeline\middlewares;

use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\database\Connection;
use app\libs\http\Request;
use app\libs\http\Response;


final class ExceptionHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware{

    public function handler(Request $request, Response $response):void{
        try{
            $this -> handlerNext($request, $response);
        }catch(\PDOException $ex){
            trigger_error($ex -> getMessage(), E_USER_ERROR);
            $conn = Connection::get();
            $response->setMessage("");
            $response->setError("Error interno. Consulte con el administrador del sistema");
            $response->send();
        }catch(\Exception $ex){
            $conn = Connection::get();
            if($conn->inTransaction()){
                $conn -> rollBack();
            }
            $response->setMessage("");
            $response->setError("{$ex->getMessage()}");
            $response->send();
        }
    }
}