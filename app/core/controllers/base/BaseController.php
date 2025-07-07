<?php

namespace app\core\controllers\base;

use app\libs\http\Request;
use app\libs\http\Response;

/**
 * Clase BaseController
 *
 * Clase base para todos los controladores del sistema. Provee funcionalidades comunes
 * como renderizado de vistas, generación de breadcrumbs, y manejo de scripts/estilos.
 *
 * @package app\core\controllers\base
 */
class BaseController {

    /**
     * @var string Ruta a la vista actual.
     */
    protected $view;

    /**
     * @var array Lista de scripts asociados a la vista.
     */
    protected $scripts;

    /**
     * @var array Lista de estilos asociados a la vista.
     */
    protected $styles;

    /**
     * @var array Arreglo que contiene los breadcrumbs generados para la vista.
     */
    protected $breadcrumbs = [];

    /**
     * @var string Nombre del controlador actual.
     */
    protected $currentController = '';

    /**
     * @var string Nombre de la acción actual.
     */
    protected $currentAction = '';

    /**
     * Constructor de BaseController.
     *
     * @param array $scripts Lista de scripts a incluir.
     * @param array $styles Lista de estilos a incluir.
     */
    public function __construct($scripts = [], $styles = []) {
        $this->view = "";
        $this->scripts = $scripts;
        $this->styles = $styles;
    }

    /**
     * Genera los breadcrumbs en base al controlador y acción de la request.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @return void
     */
    protected function generarBreadcrumbs(Request $request): void {
        $controller = $request->getController();
        $action = $request->getAction();

        $map = [
            "home"      => "Inicio",
            "producto"  => "Productos",
            "user"      => "Usuarios",
            "sale"      => "Ventas"
        ];

        $label = $map[$controller] ?? ucfirst($controller);

        $this->breadcrumbs = [
            ["label" => "Inicio", "url" => APP_URL . "/home/index"]
        ];

        if ($controller !== "home") {
            $this->breadcrumbs[] = [
                "label" => $label,
                "url"   => APP_URL . "/$controller"
            ];
        }

        if (!in_array($action, ["index", ""])) {
            $acciones = [
                "create" => "Crear",
                "edit" => "Editar",
                "delete" => "Eliminar"
            ];

            $actionLabel = $acciones[$action] ?? ucfirst($action);

            $this->breadcrumbs[] = [
                "label" => $actionLabel,
                "url"   => ""
            ];
        }
    }

    /**
     * Establece la vista actual en base a la request.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @return void
     */
    public function setCurrentView(Request $request): void {
        $this->view = $request->getController() . "/" . $request->getAction() . ".php";
    }

    /**
     * Renderiza la vista principal del sistema.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @return void
     */
    protected function render(Request $request): void {
        $this->setCurrentView($request);
        $this->currentController = $request->getController();
        $this->currentAction = $request->getAction();

        if (empty($this->breadcrumbs)) {
            $this->generarBreadcrumbs($request);
        }

        require_once APP_FILE_TEMPLATE;
    }

    /**
     * Renderiza la vista de inicio de sesión.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @return void
     */
    protected function renderLogin(Request $request): void {
        $this->setCurrentView($request);
        $this->currentController = $request->getController();
        $this->currentAction = $request->getAction();

        require_once APP_FILE_LOGIN;
    }

    /**
     * Renderiza la vista de cierre de sesión.
     *
     * @param Request $request Instancia de la solicitud HTTP.
     * @return void
     */
    protected function renderLogOut(Request $request): void {
        $this->setCurrentView($request);
        $this->currentController = $request->getController();
        $this->currentAction = $request->getAction();

        require_once APP_FILE_LOGOUT;
    }

    /**
     * Renderiza una vista de error con parámetros personalizados.
     *
     * @param string $view Nombre de la vista de error (sin extensión).
     * @param array $params Parámetros que serán extraídos como variables en la vista.
     * @return void
     */
    public static function renderError(string $view, array $params = []): void {
        extract($params);
        require_once APP_DIR_ERRORS . "/{$view}.php";
    }
}
