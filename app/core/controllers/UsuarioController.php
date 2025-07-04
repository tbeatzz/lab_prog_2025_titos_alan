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
final class UsuarioController extends BaseController implements InterfaceController
{

    /**
     * Vista principal del módulo.
     */
    public function index(Request $request, Response $response): void
    {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Vista principal del módulo de edición.
     */
    public function edit(Request $request, Response $response): void
    {
        // Obtener el ID desde la URL
        $id = (int) $request->getParameterValue('id', 0);
        if (!is_numeric($id) || $id <= 0) {
            throw new \Exception('ID de usuario inválido');
        }

        // Cargar datos del usuario
        $service = new UsuarioService();
        $dto = $service->load($id);
        if (!$dto) {
            throw new \Exception('Usuario no encontrado');
        }

        // Agregar script
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";

        // Pasar datos a la vista
        $response->setResult($dto->toArray());

        $this->render($request, 'usuario/edit.php');
    }

    /**
     * Carga un usuario por ID.
     */
    public function load(Request $request, Response $response): void
    {
        $id = (int) $request->getId();

        $service = new UsuarioService();
        $dto = $service->load($id);

        $response->setResult($dto->toArray());
        $response->send();
    }

    /**
     * Vista para crear usuario 
     */
    public function create(Request $request, Response $response): void
    {
        $this->scripts[] = "app/js/{$request->getController()}/{$request->getAction()}.js";
        $this->render($request);
    }

    /**
     * Guarda un nuevo usuario 
     */
    public function save(Request $request, Response $response): void
    {
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
        try {
            // Obtener datos del cuerpo de la solicitud
            $data = $request->getDataFromInput();
            error_log("Datos recibidos del cuerpo: " . print_r($data, true));

            // Obtener ID de la URL
            $idFromUrl = (int) $request->getParameterValue('id', 0);
            error_log("ID desde la URL: $idFromUrl");

            // Obtener ID del cuerpo (si existe)
            $idFromBody = isset($data['id']) ? (int) $data['id'] : 0;
            error_log("ID desde el cuerpo: $idFromBody");

            // Usar el ID de la URL si es válido, de lo contrario usar el del cuerpo
            $id = $idFromUrl > 0 ? $idFromUrl : $idFromBody;
            if ($id <= 0) {
                throw new \Exception("ID de usuario inválido");
            }

            // Asegurar que el ID esté en los datos
            $data['id'] = $id;
            error_log("ID final utilizado: $id");

            // Crear DTO y actualizar
            $dto = new UsuarioDto($data);
            $service = new UsuarioService();
            $service->update($dto);

            // Volver a cargar el usuario actualizado
            $updatedDto = $service->load($id);
            if (!$updatedDto) {
                throw new \Exception("No se pudo cargar el usuario actualizado");
            }

            $response->setMessage("<p>Se modificó el usuario correctamente</p>");
            $response->setResult($updatedDto->toArray());
            $response->send();
        } catch (\Exception $e) {
            error_log("Error en UsuarioController::update: " . $e->getMessage());
            $response->setMessage("<p>Error al actualizar el usuario: {$e->getMessage()}</p>");
            
            $response->send();
        }
    }
    /**
     * Elimina un usuario 
     */
    public function delete(Request $request, Response $response): void
    {
        $dto = new UsuarioDto($request->getDataFromInput());
        $service = new UsuarioService();
        $service->delete($dto);

        $response->setMessage("<p>Se eliminó el usuario correctamente</p>");
        $response->send();
    }

    /**
     * Lista de usuarios con filtros.
     */
    public function list(Request $request, Response $response): void
    {
        // Obtener datos del cuerpo JSON (para POST con JSON)
        $inputData = $request->getDataFromInput();

        // Combinar con parámetros 
        $filters = [
            "perfil" => $inputData['perfil'] ?? $request->getParameterValue("perfil", null),
            "correo" => $inputData['correo'] ?? $request->getParameterValue("correo", null),
            "limit"  => $inputData['limit'] ?? $request->getParameterValue("limit", null)
        ];

        $service = new UsuarioService();
        $usuarios = $service->list($filters);

        $response->setResult($usuarios);
        $response->send();
    }

    /**
     * Habilita un usuario por ID.
     */
    public function enable(Request $request, Response $response): void
    {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->enable($id);
        $response->setMessage("<p>El usuario fue habilitado.</p>");
        $response->send();
    }

    /**
     * Deshabilita un usuario por ID.
     */
    public function disable(Request $request, Response $response): void
    {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->disable($id);
        $response->setMessage("<p>El usuario fue deshabilitado.</p>");
        $response->send();
    }

    /**
     * Marca para restablecer contraseña.
     */
    public function reset(Request $request, Response $response): void
    {
        $id = (int) $request->getId();
        $service = new UsuarioService();
        $service->reset($id);
        $response->setMessage("<p>La contraseña fue restablecida.</p>");
        $response->send();
    }
}
