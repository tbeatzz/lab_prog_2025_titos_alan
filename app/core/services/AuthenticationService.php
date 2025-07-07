<?php

namespace app\core\services;


use app\core\models\dao\UsuarioDao;
use app\core\models\dto\LoginDto;
use app\libs\database\Connection;



final class AuthenticationService{

    public function login(LoginDto $login): void{
        $conn = Connection::get();

        //AUTENTICACIÓN DEL USUARIO
        $usuarioDao = new UsuarioDao($conn);
        $usuario = $usuarioDao->login($login->getUserName());

        if(!password_verify($login->getPassword(), $usuario["clave"])){
            throw new \Exception("El usuario o la clave es incorrecta.");
        }
        if($usuario["estado"] !== 1){
            throw new \Exception("Su cuenta esta inactiva.");
        }
        if($usuario["resetPass"] !== 0){
            throw new \Exception("Su clave ha caducado.");
        }
        //Se registran las variables de sessión
        $_SESSION["token"] = APP_TOKEN;
        $_SESSION["usuarioId"] = (int)$usuario["id"];
        $_SESSION["usuario"] = $usuario["nombres"];
        $_SESSION["perfil"] = $usuario["perfil"];
    }

    public function logout():void{
        session_unset();
        if (ini_get("session.use_cookies")){
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"],
            $params["domain"], $params["secure"], $params["httponly"]);
            }
        session_destroy();
    }

}