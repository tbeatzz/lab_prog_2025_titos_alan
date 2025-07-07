<?php

namespace app\core\controllers;

use app\core\controllers\base\BaseController;
use app\libs\http\Request;
use app\libs\http\Response;
use app\core\services\UsuarioService;
use app\core\models\dto\UsuarioDto;


final class CuentaController extends BaseController {

    public function index(Request $request, Response $response): void
    {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Devuelve los datos del usuario logueado.
     */
    public function misDatos(Request $request, Response $response): void {
        $service = new UsuarioService();

        $dto = $service->load((int) $_SESSION["usuarioId"]);

        $response->setResult($dto->toArray());
        $response->send();
    }

    /**
     * Cambia la contraseña del usuario logueado.
     */
    public function editPassword(Request $request, Response $response): void {
        $data = $request->getDataFromInput();

        $newPassword = $data["newPassword"] ?? "";

        if (empty($newPassword)) {
            throw new \Exception("La nueva contraseña no puede estar vacía.");
        }

        $service = new UsuarioService();
        $service->updatePassword((int) $_SESSION["usuarioId"], $newPassword);

        $response->setMessage("Contraseña actualizada correctamente.");
        $response->send();
    }

    public function editDatos(Request $request, Response $response): void
    {
        $data = $request->getDataFromInput();

        // Agregar el ID del usuario logueado
        $data['id'] = (int) $_SESSION["usuarioId"];

        // DTO con los datos
        $dto = new UsuarioDto($data);

        // Servicio: actualizar solo los datos personales
        $service = new UsuarioService();
        $service->update($dto);

        // Volver a cargar el usuario actualizado
        $updatedDto = $service->load($dto->getId());
        if (!$updatedDto) {
            throw new \Exception("No se pudo cargar el usuario actualizado");
        }

        $response->setMessage("Se modificaron tus datos correctamente.");
        $response->setResult($updatedDto->toArray());
        $response->send();
    }


}
