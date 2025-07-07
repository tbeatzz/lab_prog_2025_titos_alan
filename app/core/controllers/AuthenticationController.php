<?php

namespace app\core\controllers;

use app\core\controllers\base\BaseController;
use app\core\models\dto\LoginDto;
use app\core\services\AuthenticationService;
use app\libs\http\Response;
use app\libs\http\Request;

/**
 * Clase AuthenticationController
 *
 * Controlador responsable de la autenticación de usuarios. Gestiona el ingreso y cierre de sesión,
 * delegando la lógica de negocio al servicio AuthenticationService.
 *
 * @package app\core\controllers
 */
final class AuthenticationController extends BaseController {

    /**
     * Muestra la vista de inicio de sesión.
     *
     * Este método agrega el script correspondiente a la vista y llama al renderizado del formulario
     * de login.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @param Response $response Instancia de la respuesta HTTP.
     * @return void
     */
    public function index(Request $request, Response $response): void {
        // Agrega el archivo JS correspondiente al controlador y acción
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        
        // Renderiza la vista de login
        $this->renderLogin($request);
    }

    /**
     * Procesa el intento de inicio de sesión del usuario.
     *
     * Crea un DTO con los datos ingresados, invoca el servicio de autenticación, 
     * y gestiona posibles errores.
     *
     * @param Request $request Instancia de la solicitud HTTP con los datos del formulario.
     * @param Response $response Instancia de la respuesta HTTP.
     * @return void
     */
    public function login(Request $request, Response $response): void {
        try {
            // Crea un DTO con los datos del formulario
            $dto = new LoginDto($request->getDataFromInput());
            
            // Instancia el servicio de autenticación y realiza el login
            $service = new AuthenticationService();
            $service->login($dto);

            // Establece un mensaje de éxito
            $response->setMessage("OK");
        } catch (\Exception $e) {
            // Maneja errores y establece código HTTP y mensaje
            $response->setError(400);
            $response->setMessage($e->getMessage());
        }

        // Envía la respuesta como JSON o similar
        $response->send();
    }

    /**
     * Cierra la sesión del usuario y muestra la vista de cierre.
     *
     * Utiliza el servicio de autenticación para cerrar sesión y redirige al login tras 5 segundos.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @param Response $response Instancia de la respuesta HTTP.
     * @return void
     */
    public function logout(Request $request, Response $response): void {
        // Instancia el servicio y ejecuta el cierre de sesión
        $service = new AuthenticationService();
        $service->logout();

        // Establece la vista actual
        $this->setCurrentView($request);

        // Redirige automáticamente al login después de 5 segundos
        header("refresh:5;url=" . APP_URL . "/authentication/index");

        // Renderiza la vista de logout
        require_once APP_FILE_LOGOUT;
    }
}
