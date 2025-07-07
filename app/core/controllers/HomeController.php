<?php

namespace app\core\controllers;

use app\core\controllers\base\BaseController;
use app\libs\http\Response;
use app\libs\http\Request;

final class HomeController extends BaseController {
    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
       
        $this->render($request);
    }

}