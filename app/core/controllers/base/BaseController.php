<?php

namespace app\core\controllers\base;

use app\libs\http\Request;
use app\libs\http\Response;

class BaseController{

    protected $view, $scripts, $styles;
    protected $breadcrumbs = [];
    protected $currentController = '';
    protected $currentAction = '';


    public function __construct($scripts = [], $styles = []){
        $this->view = "";
        $this->scripts = $scripts;
        $this->styles = $styles;
    }

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


    public function setCurrentView(Request $request):void{
        $this->view = $request->getController() . "/" . $request->getAction() . ".php";
    }


    protected function render(Request $request): void {
        $this->setCurrentView($request);
        $this->currentController = $request->getController();
        $this->currentAction = $request->getAction();

        if (empty($this->breadcrumbs)) {
            $this->generarBreadcrumbs($request);
        }

        require_once APP_FILE_TEMPLATE;
    }




}