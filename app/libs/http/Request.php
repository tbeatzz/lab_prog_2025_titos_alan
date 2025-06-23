<?php

namespace app\libs\http;

final class Request{

    //desde el cliente ya viene el controlador y accion
    private $controller, $action;

    public function __construct(){
        $this->setController($_GET["contoller"] ?? APP_DEFAULT_CONTROLLER);
        $this->setAction($_GET["action"] ?? APP_DEFAULT_ACTION);
    }   

    // metodos
    public function getMethoD(): string{
        return $_SERVER["REQUEST_METHOD"];
    }
    public function setMethod(): string{
        
    }

    public function getController(): ?string{
        return $this->controller;
    }
    public function setController(?string $controller): void{
        $this->controller = $controller;
    }

    public function getAction(): ?string{
        return $this->action;
    }
    public function setAction(?string $action): void{
        $this->action = $action;
    }

    public function getId(): ?string{
        return $this->getParameterValue("id", null);
    }

    public function getDataFromInput():?array{
        return json_decode(file_get_contents("php://input"), true);
    }

    public function getParameterValue(string $paramName, ?string $defultValue): ?string{
        $value = null;
        switch ($this->getMethod()) {
            case 'GET':
                $value = $_GET[$paramName] ?? $defultValue;
                break;
            case 'POST':
                $value = $_POST[$paramName] ?? $defultValue;
                break;
            
        }
    }
}