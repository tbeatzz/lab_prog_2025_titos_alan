<?php

namespace app\core\services;

use app\core\models\dao\UsuarioDao;
use app\core\models\dto\LoginDto;
use app\libs\database\Connection;

/**
 * Servicio que maneja la autenticación de usuarios.
 * 
 * Realiza el login verificando credenciales y gestiona el cierre de sesión.
 */
final class AuthenticationService
{
    /**
     * Inicia sesión del usuario.
     * 
     * Valida las credenciales recibidas, verifica el estado del usuario y
     * registra las variables necesarias en la sesión.
     *
     * @param LoginDto $login DTO que contiene los datos de login del usuario.
     * @throws \Exception Si el usuario no existe, la contraseña es incorrecta, el estado es inactivo o la clave ha caducado.
     */
    public function login(LoginDto $login): void
    {
        $conn = Connection::get();

        // Instancia el DAO de usuarios
        $usuarioDao = new UsuarioDao($conn);

        // Busca el usuario por nombre de usuario o correo
        $usuario = $usuarioDao->login($login->getUserName());

        // Verifica que la contraseña ingresada coincida con la almacenada
        if (!password_verify($login->getPassword(), $usuario["clave"])) {
            throw new \Exception("El usuario o la clave es incorrecta.");
        }

        // Verifica que la cuenta esté activa
        if ($usuario["estado"] !== 1) {
            throw new \Exception("Su cuenta está inactiva.");
        }

        // Verifica que la clave no esté marcada como caducada
        if ($usuario["resetPass"] !== 0) {
            throw new \Exception("Su clave ha caducado.");
        }

        // Guarda variables esenciales en la sesión
        $_SESSION["token"] = APP_TOKEN;
        $_SESSION["usuarioId"] = (int)$usuario["id"];
        $_SESSION["usuario"] = $usuario["nombres"];
        $_SESSION["perfil"] = $usuario["perfil"];
    }

    /**
     * Cierra la sesión actual eliminando los datos de sesión y la cookie de sesión.
     */
    public function logout(): void
    {
        // Elimina todas las variables de sesión
        session_unset();

        // Si se usan cookies de sesión, elimina la cookie asociada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"],
                $params["domain"], $params["secure"], $params["httponly"]);
        }

        // Destruye la sesión
        session_destroy();
    }
}
