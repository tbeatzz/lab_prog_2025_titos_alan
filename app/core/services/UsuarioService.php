<?php

namespace app\core\services;

use app\core\models\dao\UserDao;
use app\core\models\dto\UserDto;
use app\core\models\dto\base\InterfaceDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

final class UserService implements InterfaceService {

    /**
     * Retorna un DTO con los datos del usuario buscado por ID
     */
    public function load(int $id): InterfaceDto {
        $dao = new UserDao(Connection::get());
        $data = $dao->load($id);
        return new UserDto($data);
    }

    /**
     * Guarda un nuevo usuario
     */
    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        unset($data["id"]);
        $dao = new UserDao(Connection::get());
        $dao->save($data);
    }

    /**
     * Actualiza un usuario existente
     */
    public function update(InterfaceDto $dto): void {
        $this->validate($dto);
        $dao = new UserDao(Connection::get());
        $dao->update($dto->toArray());
    }

    /**
     * Elimina un usuario
     */
    public function delete(InterfaceDto $dto): void {
        $dao = new UserDao(Connection::get());
        $dao->delete($dto->getId());
    }

    /**
     * Lista usuarios usando filtros como cuenta, apellido, estado, etc.
     */
    public function list(array $filters): array {
        $dao = new UserDao(Connection::get());
        return $dao->list($filters);
    }

    /**
     * Validación de campos obligatorios del usuario
     */
    private function validate(UserDto $dto): void {
        $errores = [];

        if (trim($dto->getApellido()) === "") {
            $errores[] = "<p>El <strong>apellido</strong> es obligatorio.</p>";
        }

        if (trim($dto->getNombres()) === "") {
            $errores[] = "<p>El <strong>nombre</strong> es obligatorio.</p>";
        }

        if (trim($dto->getCuenta()) === "") {
            $errores[] = "<p>El <strong>usuario/cuenta</strong> es obligatorio.</p>";
        }

        if (trim($dto->getClave()) === "") {
            $errores[] = "<p>La <strong>clave</strong> es obligatoria.</p>";
        }

        if (trim($dto->getCorreo()) === "") {
            $errores[] = "<p>El <strong>correo electrónico</strong> es obligatorio.</p>";
        }

        if (!empty($errores)) {
            throw new \Exception(implode("", $errores));
        }
    }
}
