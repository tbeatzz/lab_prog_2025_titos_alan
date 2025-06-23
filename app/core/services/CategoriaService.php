<?php

namespace app\core\services;

use app\core\models\dao\CategoriaDao;
use app\core\models\dto\base\InterfaceDto;
use app\core\models\dto\CategoriaDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

final class CategoriaService implements InterfaceService {

    /**
     * Devuelve una categoría en base a su ID
     */
    public function load(int $id): InterfaceDto {
        $dao = new CategoriaDao(Connection::get());
        $data = $dao->load($id);
        return new CategoriaDto($data);
    }

    /**
     * Guarda una nueva categoría
     */
    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        unset($data["id"]);
        $dao = new CategoriaDao(Connection::get());
        $dao->save($data);
    }

    /**
     * Actualiza una categoría existente
     */
    public function update(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        $dao = new CategoriaDao(Connection::get());
        $dao->update($data);
    }

    /**
     * Elimina una categoría (por ID del DTO)
     */
    public function delete(InterfaceDto $dto): void {
        $dao = new CategoriaDao(Connection::get());
        $dao->delete($dto->getId());
    }

    /**
     * Lista categorías con filtros opcionales
     */
    public function list(array $filters): array {
        $dao = new CategoriaDao(Connection::get());
        return $dao->list($filters);
    }

    /**
     * Valida la categoría (nombre obligatorio)
     */
    private function validate(CategoriaDto $dto): void {
        if (trim($dto->getNombre()) === "") {
            throw new \Exception("<p>El <strong>nombre</strong> de la categoría es obligatorio.</p>");
        }
    }
}