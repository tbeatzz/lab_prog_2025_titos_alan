<?php

namespace app\core\controllers;

use app\core\controllers\base\BaseController;
use app\libs\http\Request;
use app\libs\http\Response;
use app\core\services\UsuarioService;
use app\core\models\dto\UsuarioDto;

/**
 * Controlador responsable de las operaciones de cuenta del usuario actual.
 * Permite visualizar y editar los datos personales del usuario logueado,
 * así como cambiar su contraseña.
 */
final class CuentaController extends BaseController {

    /**
     * Renderiza la vista principal de la cuenta del usuario.
     *
     * @param Request $request  Objeto con datos de la solicitud HTTP.
     * @param Response $response Objeto para enviar la respuesta HTTP.
     */
    public function index(Request $request, Response $response): void
    {
        // Agrega el script JS correspondiente al controlador/acción actual
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";

        // Renderiza la vista asignada a la acción 'index'
        $this->render($request);
    }

    /**
     * Devuelve los datos del usuario actualmente logueado.
     * Se utiliza para llenar formularios de perfil, etc.
     *
     * @param Request $request  Objeto con datos de la solicitud HTTP.
     * @param Response $response Objeto para enviar la respuesta HTTP.
     *
     * @throws \Exception Si no se encuentra el usuario.
     */
    public function misDatos(Request $request, Response $response): void {
        // Servicio para obtener datos de usuarios
        $service = new UsuarioService();

        // Carga los datos del usuario usando el ID de sesión
        $dto = $service->load((int) $_SESSION["usuarioId"]);

        // Retorna los datos en formato array
        $response->setResult($dto->toArray());
        $response->send();
    }

    /**
     * Permite cambiar la contraseña del usuario logueado.
     * Se espera un campo "newPassword" en el cuerpo de la solicitud.
     *
     * @param Request $request  Objeto con datos de la solicitud HTTP.
     * @param Response $response Objeto para enviar la respuesta HTTP.
     *
     * @throws \Exception Si no se proporciona una nueva contraseña.
     */
    public function editPassword(Request $request, Response $response): void {
        // Obtiene los datos enviados en la solicitud
        $data = $request->getDataFromInput();

        // Extrae la nueva contraseña o establece un string vacío
        $newPassword = $data["newPassword"] ?? "";

        // Valida que la nueva contraseña no esté vacía
        if (empty($newPassword)) {
            throw new \Exception("La nueva contraseña no puede estar vacía.");
        }

        // Actualiza la contraseña usando el servicio
        $service = new UsuarioService();
        $service->updatePassword((int) $_SESSION["usuarioId"], $newPassword);

        // Devuelve mensaje de éxito
        $response->setMessage("Contraseña actualizada correctamente.");
        $response->send();
    }

    /**
     * Permite actualizar los datos personales del usuario logueado.
     * Se esperan los campos modificables del usuario en el body.
     *
     * @param Request $request  Objeto con datos de la solicitud HTTP.
     * @param Response $response Objeto para enviar la respuesta HTTP.
     *
     * @throws \Exception Si no se puede recargar el usuario actualizado.
     */
    public function editDatos(Request $request, Response $response): void
    {
        // Obtiene los datos enviados por el usuario
        $data = $request->getDataFromInput();

        // Asegura que se actualicen los datos del usuario en sesión
        $data['id'] = (int) $_SESSION["usuarioId"];

        // Construye el DTO con los datos proporcionados
        $dto = new UsuarioDto($data);

        // Invoca el servicio para actualizar los datos
        $service = new UsuarioService();
        $service->update($dto);

        // Vuelve a cargar los datos actualizados para confirmar el cambio
        $updatedDto = $service->load($dto->getId());

        if (!$updatedDto) {
            throw new \Exception("No se pudo cargar el usuario actualizado");
        }

        // Responde con los datos actualizados
        $response->setMessage("Se modificaron tus datos correctamente.");
        $response->setResult($updatedDto->toArray());
        $response->send();
    }
}
