<?php
namespace app\core\controllers;

use app\core\controllers\base\BaseController;
use app\libs\http\Request;
use app\libs\http\Response;

final class ItemController extends BaseController {

    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

  
}
