<?php

namespace app\core\services;

use app\core\models\dao\ProductoDao;
use app\core\models\dto\ProductoDto;
use app\core\models\dto\base\InterfaceDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

final class ProductoService implements InterfaceService {

    /**
     * Carga un producto por su ID y lo devuelve como DTO
     */
    public function load(int $id): InterfaceDto {
        $dao = new ProductoDao(Connection::get());
        $data = $dao->load($id);
        return new ProductoDto($data);
    }

    /**
     * Guarda un nuevo producto en la base de datos
     */
    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        unset($data["id"]);
        $dao = new ProductoDao(Connection::get());
        $dao->save($data);
    }

    /**
     * Actualiza los datos de un producto existente
     */
    public function update(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        $dao = new ProductoDao(Connection::get());
        $dao->update($data);
    }

    /**
     * Elimina un producto por ID
     */
    public function delete(InterfaceDto $dto): void {
        $dao = new ProductoDao(Connection::get());
        $dao->delete($dto->getId());
    }

    /**
     * Lista productos con filtros opcionales
     */
    public function list(array $filters): array {
        $dao = new ProductoDao(Connection::get());
        return $dao->list($filters);
    }

    /**
     * Valida los datos de un producto
     */
    private function validate(ProductoDto $dto): void {
        $errores = [];

        if (trim($dto->getNombre()) === "") {
            $errores[] = "<p>El <strong>nombre</strong> del producto es obligatorio.</p>";
        }

        if (trim($dto->getCodigo()) === "") {
            $errores[] = "<p>El <strong>código</strong> del producto es obligatorio.</p>";
        }

        if ($dto->getCategoriaId() <= 0) {
            $errores[] = "<p>Debe seleccionar una <strong>categoría</strong> válida.</p>";
        }

        if ($dto->getPrecio() < 0) {
            $errores[] = "<p>El <strong>precio</strong> no puede ser negativo.</p>";
        }

        if ($dto->getStock() < 0) {
            $errores[] = "<p>El <strong>stock</strong> no puede ser negativo.</p>";
        }

        if (!empty($errores)) {
            throw new \Exception(implode("", $errores));
        }
    }
}
