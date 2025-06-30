<?php

namespace app\core\services;

use app\core\models\dao\UsuarioDao;
use app\core\models\dto\UsuarioDto;
use app\core\models\dto\base\InterfaceDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

/**
 * Servicio para lógica de negocio de usuarios.
 * Realiza validaciones y coordina acciones con el DAO.
 */
final class UsuarioService implements InterfaceService {

    /**
     * Carga un usuario por su ID.
     */
    public function load(int $id): InterfaceDto {
        $dao = new UsuarioDao(Connection::get());
        $data = $dao->load($id);
        return new UsuarioDto($data);
    }

    /**
     * Guarda un nuevo usuario.
     * Hash de clave incluido.
     */
    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        unset($data["id"]);

        if (!empty($data["clave"])) {
            $data["clave"] = password_hash($data["clave"], PASSWORD_DEFAULT);
        }

        $dao = new UsuarioDao(Connection::get());
        $dao->save($data);
    }

    /**
     * Actualiza un usuario existente.
     * Si no se envía clave nueva, conserva la anterior.
     */
    public function update(InterfaceDto $dto): void {
        $this->validateUpdate($dto);
        $dao = new UsuarioDao(Connection::get());
        $usuarioExistente = $dao->load($dto->getId());

        $data = $dto->toArray();

        $data["clave"] = !empty($data["clave"])
            ? password_hash($data["clave"], PASSWORD_DEFAULT)
            : $usuarioExistente["clave"];

        $dao->update($data);
    }

    /**
     * Elimina un usuario existente.
     */
    public function delete(InterfaceDto $dto): void {
        $dao = new UsuarioDao(Connection::get());
        $dao->load($dto->getId());
        $dao->delete($dto->getId());
    }

    /**
     * Lista usuarios con filtros (perfil, estado, limit...).
     */
    public function list(array $filters): array {
        $dao = new UsuarioDao(Connection::get());
        return $dao->list($filters);
    }

    /**
     * Habilita un usuario.
     */
    public function enable(int $id): void {
        $dao = new UsuarioDao(Connection::get());
        $dao->load($id);
        $dao->enable($id);
    }

    /**
     * Deshabilita un usuario.
     */
    public function disable(int $id): void {
        $dao = new UsuarioDao(Connection::get());
        $dao->load($id);
        $dao->disable($id);
    }

    /**
     * Activa el flag para restablecer contraseña.
     */
    public function reset(int $id): void {
        $dao = new UsuarioDao(Connection::get());
        $dao->load($id);
        $dao->reset($id);
    }

    /**
     * Validaciones al guardar un usuario.
     */
    private function validate(UsuarioDto $dto): void {
        if ($dto->getNombres() === "") {
            throw new \Exception("<p>El <strong>nombre</strong> es obligatorio.</p>");
        }
        if ($dto->getCuenta() === "") {
            throw new \Exception("<p>El <strong>usuario</strong> es obligatorio.</p>");
        }
        if ($dto->getClave() === "") {
            throw new \Exception("<p>La <strong>clave</strong> es obligatoria.</p>");
        }
    }

    /**
     * Validaciones al actualizar un usuario.
     */
    private function validateUpdate(UsuarioDto $dto): void {
        if ($dto->getNombres() === "") {
            throw new \Exception("<p>El <strong>nombre</strong> es obligatorio.</p>");
        }
        if ($dto->getCuenta() === "") {
            throw new \Exception("<p>El <strong>usuario</strong> es obligatorio.</p>");
        }
    }
}