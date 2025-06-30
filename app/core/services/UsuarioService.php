<?php

namespace app\core\services;

use app\core\models\dao\UsuarioDao;
use app\core\models\dto\UsuarioDto;
use app\core\models\dto\base\InterfaceDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

final class UsuarioService implements InterfaceService {

    public function load(int $id): InterfaceDto {
        $dao = new UsuarioDao(Connection::get());
        $data = $dao->load($id);
        return new UsuarioDto($data);
    }

    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        unset($data["id"]);
        // Hashear la clave si está definida
        if (!empty($data["clave"])) {
            $data["clave"] = password_hash($data["clave"], PASSWORD_DEFAULT);
        }

        $dao = new UsuarioDao(Connection::get());
        $dao->save($data);
    }

    public function update(InterfaceDto $dto): void {
        $this->validateUpdate($dto); // Nueva validación específica para update

        $dao = new UsuarioDao(Connection::get());

        // Validación de existencia
        $usuarioExistente = $dao->load($dto->getId());

        $data = $dto->toArray();

        // Si se envió una nueva clave, hashearla
        if (!empty($data["clave"])) {
            $data["clave"] = password_hash($data["clave"], PASSWORD_DEFAULT);
        } else {
            // Si no se envió clave nueva, mantener la original
            $data["clave"] = $usuarioExistente["clave"];
        }

        $dao->update($data);
    }

    public function delete(InterfaceDto $dto): void {
        $dao = new UsuarioDao(Connection::get());
        //Validación de existencia utilizando el load del dao que ya valida si existe o no
        $dao->load($dto->getId());
        $dao->delete($dto->getId());
    }

    public function list(array $filters): array {
        $dao = new UsuarioDao(Connection::get());
        return $dao->list($filters);
    }

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

    // Métodos adicionales del servicio de Usuario

    public function enable(int $id): void {
        $dao = new UsuarioDao(Connection::get());
        // Validación
        $dao->load($id);

        $dao->enable($id);
    }

    public function disable(int $id): void {
        $dao = new UsuarioDao(Connection::get());

        // Validación
        $dao->load($id);

        $dao->disable($id);
    }

    public function reset(int $id): void {
        $dao = new UsuarioDao(Connection::get());

        // Validación
        $dao->load($id);

        $dao->reset($id);
    }

    private function validateUpdate(UsuarioDto $dto): void {
    if ($dto->getNombres() === "") {
        throw new \Exception("<p>El <strong>nombre</strong> es obligatorio.</p>");
    }
    if ($dto->getCuenta() === "") {
        throw new \Exception("<p>El <strong>usuario</strong> es obligatorio.</p>");
    }
    // Nota: no se valida que la clave esté vacía en update (porque puede no cambiar)
}

}
