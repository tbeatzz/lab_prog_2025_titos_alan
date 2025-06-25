<?php

namespace app\libs\http;

final class Request{

    private $controller, $action;
    
    public function __construct() {
        $this->setController($_GET["controller"] ?? APP_DEFAULT_CONTROLLER);
        $this->setAction($_GET["action"] ?? APP_DEFAULT_ACTION);
    }

    //***************** Getters y Setters *****************
    
    public function getMethod(): string{
        return $_SERVER["REQUEST_METHOD"];
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

    public function getParameterValue(string $paramName, ?string $defaultValue): ?string{
        $value = null;
        switch ($this->getMethod()){
            case "GET":
                $value =  $_GET[$paramName] ?? $defaultValue;
                break;
            case "POST":
                $value = $_POST[$paramName] ?? $defaultValue;
                break;
        }
        return $value;
    }

    public function getDataFromInput(): ?array{
        return json_decode(file_get_contents("php://input"), true);
    }
    
}