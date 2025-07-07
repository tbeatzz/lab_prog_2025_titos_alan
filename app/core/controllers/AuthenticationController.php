<?php

namespace app\core\controllers;

use app\core\controllers\base\BaseController;
use app\core\models\dto\LoginDto;
use app\core\services\AuthenticationService;
use app\libs\http\Response;
use app\libs\http\Request;



final class AuthenticationController extends BaseController {

   
    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->renderLogin($request);
    }

    public function login(Request $request, Response $response): void{
        $dto = new LoginDto($request->getDataFromInput());
        $service = new AuthenticationService();
        $service->login($dto);
        $response->setMessage("OK");
        $response->send();
    }

    public function logout(Request $request, Response $response): void{
        $service = new AuthenticationService();
        $service->logout();
        $this->setCurrentView($request);
        header("refresh:5;url=" . APP_URL . "/authentication/index");
        require_once APP_FILE_LOGOUT;
    }

    // public function logout(Request $request, Response $response): void {
    //      $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
    //     $this->render($request);
    // }
}
