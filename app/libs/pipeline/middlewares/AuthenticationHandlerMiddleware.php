<?php

namespace app\libs\pipeline\middlewares;

use app\libs\http\Request;
use app\libs\http\Response;
use app\libs\pipeline\middlewares\base\BaseMiddleware;
use app\libs\pipeline\middlewares\base\InterfaceMiddleware;

/**
 * Middleware de autorización.
 * 
 * Valida el perfil del usuario y determina si puede acceder a determinados controladores/acciones.
 * Si no tiene permisos, redirige a la página 403 Forbidden.
 */

final class AuthenticationHandlerMiddleware extends BaseMiddleware implements InterfaceMiddleware {
    
    public function __construct() {
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
    public function handler(Request $request, Response $response): void {
        
        session_start();
    
        if(!isset($_SESSION["token"]) || ($_SESSION["token"] != APP_TOKEN)) {
            $request->setController(APP_AUTHENTICATION_CONTROLLER);
            if($request->getAction() != APP_LOGIN_ACTION){  
                $request->setAction(APP_DEFAULT_ACTION);
            }
        }

        $this->handlerNext($request, $response);
    }

    
}