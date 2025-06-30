<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\services\AuthenticationService;

final class AuthenticationController{

    private AuthenticationService $service;

    public function __construct() {
        $this->service = new AuthenticationService();
    }

    public function index(Request $request, Response $response): void {
        require_once "../app/resources/views/authentication/index.php";
    }

    public function login(Request $request, Response $response): void {
        $cuenta = $request->getParameterValue("cuenta", "");
        $clave = $request->getParameterValue("clave", "");

        if (empty($cuenta) || empty($clave)) {
            $response->setError("Debe ingresar cuenta y clave");
            $response->setMessage("Datos incompletos");
            $response->send();
            return;
        }

        try {
            $usuario = $this->service->login($cuenta, $clave);
            $response->setResult([
                "id" => $usuario->getId(),
                "cuenta" => $usuario->getCuenta(),
                "nombres" => $usuario->getNombres(),
                "apellido" => $usuario->getApellido(),
                "perfil" => $usuario->getPerfil()
            ]);
            $response->setMessage("Login exitoso");
        } catch (\Exception $e) {
            $response->setError($e->getMessage());
            $response->setMessage("Login fallido");
        }

        $response->send();
    }




    public function logout(Request $request, Response $response): void {
        // Iniciar sesión si no está iniciada para evitar warning
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($this->service->isAuthenticated()) {
            $this->service->logout();
            $response->setMessage("Logout exitoso.");
        } else {
            $response->setMessage("No hay una sesión activa.");
        }

        $response->send();
    }
}
