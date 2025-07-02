<?php

namespace app\core\controllers;

use app\libs\http\Request;
use app\libs\http\Response;
use app\core\controllers\base\BaseController;
use app\core\controllers\base\InterfaceController;
use app\core\services\UsuarioService;
use app\core\models\dto\UsuarioDto;

/**
 * Controlador para manejar operaciones sobre usuarios.
 */
final class UsuarioController extends BaseController implements InterfaceController {

    /**
     * Vista principal del módulo.
     */
    public function index(Request $request, Response $response): void {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Carga un usuario por ID.
     */
    public function load(Request $request, Response $response): void {

        $id = (int) $request->getParameterValue("id", 0);
        $service = new UsuarioService();
        $dto = $service->load($id);

        $response->setResult($dto->toArray());
        $response->send();
    }

    /**
     * Vista para crear usuario 
     */
    public function create(Request $request, Response $response): void {
        // array_push($this->scripts, "app/js/usuario/create.js");
    }

    /**
     * Guarda un nuevo usuario 
     */
    public function save(Request $request, Response $response): void {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->save($dto);

        $response->setMessage("<p>Se agregó un nuevo usuario al sistema</p>");
        $response->send();
    }

    /**
     * Actualiza un usuario existente
     */
    public function update(Request $request, Response $response): void {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->update($dto);

        $response->setMessage("<p>Se modificó el usuario correctamente</p>");
        $response->send();
    }

    /**
     * Elimina un usuario 
     */
    public function delete(Request $request, Response $response): void {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->delete($dto);

        $response->setMessage("<p>Se eliminó el usuario correctamente</p>");
        $response->send();
    }

    /**
     * Lista de usuarios con filtros.
     */
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

    /**
     * Habilita un usuario por ID.
     */
    public function enable(Request $request, Response $response): void {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->enable($id);
        $response->setMessage("<p>El usuario fue habilitado.</p>");
        $response->send();
    }

    /**
     * Deshabilita un usuario por ID.
     */
    public function disable(Request $request, Response $response): void {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->disable($id);
        $response->setMessage("<p>El usuario fue deshabilitado.</p>");
        $response->send();
    }

    /**
     * Marca para restablecer contraseña.
     */
    public function reset(Request $request, Response $response): void {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->reset($id);
        $response->setMessage("<p>La contraseña fue restablecida.</p>");
        $response->send();
    }
}