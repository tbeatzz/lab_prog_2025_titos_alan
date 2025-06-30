<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\UsuarioService;
use app\core\models\dto\UsuarioDto;

final class UsuarioController extends BaseController implements InterfaceController {

    public function index(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/usuario/index.js");
        echo "<h1>Funciona el controlador de Usuario</h1>";
        // $this->setCurrentView($request);
        // require_once APP_FILE_TEMPLATE;
    }

    public function load(Request $request, Response $response): void {
        $service = new UsuarioService();
        $dto = $service->load((int) $request->getId());
        $response->setResult($dto->toArray());
        $response->send();
    }

    public function create(Request $request, Response $response): void {
        array_push($this->scripts, "app/js/usuario/create.js");
        // require_once APP_FILE_TEMPLATE;
    }

    public function save(Request $request, Response $response): void {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->save($dto);

        $response->setMessage("<p>Se agregó un nuevo usuario al sistema</p>");
        $response->send();
    }

    public function update(Request $request, Response $response): void {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->update($dto);

        $response->setMessage("<p>Se modificó el usuario correctamente</p>");
        $response->send();
    }

    public function delete(Request $request, Response $response): void {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->delete($dto);

        $response->setMessage("<p>Se eliminó el usuario correctamente</p>");
        $response->send();
    }

    public function list(Request $request, Response $response): void {
        $filters = [
            "nombres" => $request->getParameterValue("nombres", null),
            "limit"   => $request->getParameterValue("limit", null)
        ];

        $service = new UsuarioService();
        $usuarios = $service->list($filters);

        $response->setResult($usuarios);
        $response->send();
    }

    public function enable(Request $request, Response $response): void {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->enable($id);
        $response->setMessage("<p>El usuario fue habilitado.</p>");
        $response->send();
    }

    public function disable(Request $request, Response $response): void {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->disable($id);
        $response->setMessage("<p>El usuario fue deshabilitado.</p>");
        $response->send();
    }

    public function reset(Request $request, Response $response): void {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->reset($id);
        $response->setMessage("<p>La contraseña fue restablecida.</p>");
        $response->send();
    }
}
