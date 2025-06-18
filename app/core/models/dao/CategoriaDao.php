<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;
use app\core\model\dto\CategoriaDto;
use PDO;

/**
 * Clase DAO para la entidad Categoria.
 * Se encarga de todas las operaciones CRUD y de validación sobre la tabla 'categorias'.
 */
final class CategoriaDao extends BaseDao implements InterfaceDao{
    /**
     * Constructor de CategoriaDao
     *
     * @param \PDO|null $connection Conexión a la base de datos
     */
    public function __construct(?\PDO $connection)
    {
        parent::__construct($connection, 'categorias');
    }

    /**
     * Devuelve una categoría según su ID.
     *
     * @param int $id ID de la categoría
     * @return array Datos de la categoría
     * @throws \Exception Si no se encuentra la categoría
     */
    public function load(int $id): array
    {
        $sql = "SELECT id, nombre, descripcion FROM {$this->table} WHERE id = :id";
        $result = $this->selectQuery($sql, ["id" => $id]);

        if (count($result) === 0) {
            throw new \Exception("No se encontró la categoría con ID ($id)");
        }

        return $result[0];
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     *
     * @param array $data Datos de la categoría (nombre, descripcion)
     * @return void
     * @throws \Exception Si ya existe una categoría con el mismo nombre
     */
    public function save(array $data): void
    {
        $this->validarNombre(0, $data["nombre"]);
        $sql = "INSERT INTO {$this->table} VALUES (DEFAULT, :nombre, :descripcion)";
        $this->insertQuery($sql, $data);
    }

    /**
     * Actualiza una categoría existente.
     *
     * @param array $data Datos de la categoría (id, nombre, descripcion)
     * @return void
     * @throws \Exception Si ya existe otra categoría con el mismo nombre
     */
    public function update(array $data): void
    {
        $this->validarNombre($data["id"], $data["nombre"]);

        $sql = "UPDATE {$this->table} SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        $this->updateQuery($sql, $data);
    }

    /**
     * Elimina una categoría según su ID.
     *
     * @param int $id ID de la categoría a eliminar
     * @return void
     */
    public function delete(int $id): void
    {
        $this->deleteQuery($id);
    }

    /**
     * Lista las categorías aplicando filtros opcionales.
     *
     * @param array $filters Filtros disponibles: 'nombre', 'limit', 'offset'
     * @return array Lista de categorías
     */
    public function list(array $filters): array
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS id, nombre, descripcion FROM {$this->table}";
        $params = [];
        $where = [];

        if (isset($filters["nombre"]) && $filters["nombre"] !== "") {
            $where[] = "nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY nombre";

        if (isset($filters["limit"])) {
            $sql .= " LIMIT " . (int)$filters["limit"];
        }

        if (isset($filters["offset"])) {
            $sql .= " OFFSET " . (int)$filters["offset"];
        }

        return $this->selectQuery($sql, $params);
    }

    /**
     * Devuelve la cantidad total de registros sin límite, útil para paginación.
     *
     * @return int Número total de filas encontradas
     */
    public function foundRows(): int
    {
        return $this->getFoundRows();
    }

    /**
     * Obtiene el último ID insertado en la tabla.
     *
     * @return int ID del último registro insertado
     */
    public function getLastInserId(): int
    {
        return (int) $this->connection->lastInsertId();
    }

    /**
     * Valida que el nombre de categoría no se repita (excepto si es el mismo ID).
     *
     * @param int $id ID de la categoría actual (0 si es nueva)
     * @param string $nombre Nombre a validar
     * @return void
     * @throws \Exception Si ya existe una categoría con ese nombre
     */
    private function validarNombre(int $id, string $nombre): void
    {
        $sql = "SELECT COUNT(*) AS cantidad FROM {$this->table} WHERE nombre = :nombre AND id != :id";
        $params = [
            "nombre" => $nombre,
            "id" => $id
        ];
        $result = $this->selectQuery($sql, $params);

        if ($result[0]["cantidad"] > 0) {
            throw new \Exception("Ya existe una categoría con el nombre '{$nombre}'");
        }
    }
}