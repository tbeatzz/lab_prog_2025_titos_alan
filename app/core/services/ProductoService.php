<?php

namespace app\core\services;

use app\core\models\dao\ProductoDao;
use app\core\models\dto\ProductoDto;
use app\core\models\dto\base\InterfaceDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

/**
 * Servicio de lógica de negocio para productos.
 * Valida y coordina operaciones entre DTO y DAO.
 */
final class ProductoService implements InterfaceService {

    /**
     * Carga un producto por su ID.
     *
     * @param int $id
     * @return InterfaceDto
     */
    public function load(int $id): InterfaceDto {
        $dao = new ProductoDao(Connection::get());
        $data = $dao->load($id);
        return new ProductoDto($data);
    }

    /**
     * Guarda un nuevo producto.
     *
     * @param InterfaceDto $dto
     * @return void
     * @throws \Exception si el DTO no es válido.
     */
    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $dao = new ProductoDao(Connection::get());
        $dao->save($dto->toArray());
    }

    /**
     * Actualiza un producto existente.
     *
     * @param InterfaceDto $dto
     * @return void
     * @throws \Exception si el producto no tiene ID válido o no pasa validaciones.
     */
    public function update(InterfaceDto $dto): void {
        $this->validate($dto);

        if ($dto->getId() <= 0) {
            throw new \Exception("El <strong>ID</strong> del producto es obligatorio para actualizar.");
        }

        $dao = new ProductoDao(Connection::get());
        $dao->load($dto->getId()); // Asegura que exista antes de actualizar
        $dao->update($dto->toArray());
    }

    /**
     * Elimina un producto por su ID.
     *
     * @param InterfaceDto $dto
     * @return void
     * @throws \Exception si el ID no es válido.
     */
    public function delete(InterfaceDto $dto): void {
        if ($dto->getId() <= 0) {
            throw new \Exception("El <strong>ID</strong> del producto es obligatorio para eliminar.");
        }

        $dao = new ProductoDao(Connection::get());
        $dao->load($dto->getId()); // Asegura existencia
        $dao->delete($dto->getId());
    }

    /**
     * Lista productos según filtros.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters): array {
        $dao = new ProductoDao(Connection::get());
        return $dao->list($filters);
    }

    /**
     * Valida reglas de negocio del producto.
     *
     * @param ProductoDto $dto
     * @throws \Exception si alguna regla no se cumple.
     */
    private function validate(ProductoDto $dto): void {
        if ($dto->getNombre() === "") {
            throw new \Exception("El <strong>nombre</strong> es obligatorio.");
        }

        if ($dto->getCodigo() === "") {
            throw new \Exception("El <strong>código</strong> es obligatorio.");
        }

        if ($dto->getPrecio() <= 0) {
            throw new \Exception("El <strong>precio</strong> debe ser mayor a 0.");
        }

        if ($dto->getCategoriaId() <= 0) {
            throw new \Exception("Debes seleccionar una <strong>categoría</strong> válida.");
        }
    }

    public function getCantidadProductos(): int {
        $dao = new ProductoDao(Connection::get());
        return $dao->listCantidad();
    }
}
