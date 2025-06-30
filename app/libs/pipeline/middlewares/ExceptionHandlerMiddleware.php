<?php
namespace app\libs\pipeline\middlewares;

use app\libs\database\Connection;
use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;
use app\libs\http\Request;
use app\libs\http\Response;

/**
 * Descripción de ExceptionHandlerMiddleware
 * Captura las excepcion que puedan ocurrir en el resto de la cadena de resposnabilidades.
 * 
 * @author Daniel Ivan Reales
 */

 final class ExceptionHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware{

    public function __construct(){
        parent::__construct();
    }

    public function handler(Request $request, Response $response): void{
        try{
            $this->handlerNext($request, $response);
        }
        catch (\PDOException $ex){
            //FALTA IMPLEMENTAR EL LUNES
            // trigger_error($ex->getMessage(), E_USER_ERROR);
            $conn = Connection::get();
            if($conn->inTransaction()){
                $conn->rollBack();
            }

            $response->setMessage("");
            $response->setError("Error interno. Consulte con el administrador del sistema.");
            $response->send();
        }
        catch (\Exception $ex) {
            $conn = Connection::get();
            if($conn->inTransaction()){
                $conn->rollBack();
            }

            $response->setMessage("");
            $response->setError("{$ex->getMessage()}");
            $response->send();
        }
    }
 }