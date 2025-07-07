<?php

namespace app\core\services;

use app\core\models\dao\CategoriaDao;
use app\core\models\dto\base\InterfaceDto;
use app\core\models\dto\CategoriaDto;
use app\core\services\base\InterfaceService;
use app\libs\database\Connection;

/**
 * Servicio para manejar la lógica de negocio relacionada con la entidad Categoría.
 * Implementa validaciones y delega operaciones al DAO.
 */
final class CategoriaService implements InterfaceService {

    /**
     * Carga una categoría por su ID
     *
     * @param int $id ID de la categoría
     * @return InterfaceDto Objeto de transferencia de datos de la categoría
     * @throws \Exception Si no se encuentra la categoría
     */
    public function load(int $id): InterfaceDto {
        $dao = new CategoriaDao(Connection::get());
        $data = $dao->load($id); // El DAO lanza excepción si no encuentra
        return new CategoriaDto($data);
    }

    /**
     * Guarda una nueva categoría
     *
     * @param InterfaceDto $dto Objeto de transferencia con los datos de la categoría
     * @throws \Exception Si hay errores de validación o duplicación
     */
    public function save(InterfaceDto $dto): void {
        $this->validate($dto);
        $data = $dto->toArray();
        unset($data["id"]); // ID autoincremental
        $dao = new CategoriaDao(Connection::get());
        $dao->save($data);
    }

    /**
     * Actualiza una categoría existente
     *
     * @param InterfaceDto $dto Objeto con ID y datos actualizados
     * @throws \Exception Si faltan datos o ya existe otra categoría con ese nombre
     */
    public function update(InterfaceDto $dto): void {
        $this->validate($dto);

        if ($dto->getId() <= 0) {
            throw new \Exception("<p>El <strong>ID</strong> de la categoría es obligatorio para actualizar.</p>");
        }

        $dao = new CategoriaDao(Connection::get());

        // Validar existencia
        $dao->load($dto->getId());

        $dao->update($dto->toArray());
    }

    /**
     * Elimina una categoría existente
     *
     * @param InterfaceDto $dto Objeto con el ID de la categoría a eliminar
     * @throws \Exception Si el ID es inválido o no existe
     */
    public function delete(InterfaceDto $dto): void {
        if ($dto->getId() <= 0) {
            throw new \Exception("<p>El <strong>ID</strong> de la categoría es obligatorio para eliminar.</p>");
        }

        $dao = new CategoriaDao(Connection::get());

        // Validar existencia
        $dao->load($dto->getId());

        $dao->delete($dto->getId());
    }

    /**
     * Lista categorías utilizando filtros opcionales
     *
     * @param array $filters Filtros como 'nombre', 'limit', 'offset'
     * @return array Lista de resultados
     */
    public function list(array $filters): array {
        $dao = new CategoriaDao(Connection::get());
        return $dao->list($filters);
    }

    /**
     * Valida los datos de una categoría
     *
     * @param CategoriaDto $dto Objeto a validar
     * @throws \Exception Si el nombre está vacío
     */
    private function validate(CategoriaDto $dto): void {
        if (trim($dto->getNombre()) === "") {
            throw new \Exception("<p>El <strong>nombre</strong> de la categoría es obligatorio.</p>");
        }
    }

    public function getCantidadCategorias(): int {
        $dao = new CategoriaDao(Connection::get());
        return $dao->listCantidad();
    }
}
