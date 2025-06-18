<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;

/**
 * DAO para la entidad Producto.
 * Gestiona el acceso a la tabla 'productos' (CRUD + filtros).
 */
final class ProductoDao extends BaseDao implements InterfaceDao{
    /**
     * Constructor del DAO de Producto
     *
     * @param \PDO|null $connection Conexión a la base de datos
     */
    public function __construct(?\PDO $connection)
    {
        parent::__construct($connection, 'productos');
    }

    /**
     * Carga un producto por su ID
     *
     * @param int $id Identificador del producto
     * @return array Datos del producto
     * @throws \Exception Si no se encuentra el producto
     */
    public function load(int $id): array
    {
        $sql = "SELECT id, nombre, codigo, descripcion, categoriaId, precio, stock FROM {$this->table} WHERE id = :id";
        $result = $this->selectQuery($sql, ["id" => $id]);

        if (count($result) === 0) {
            throw new \Exception("No se encontró el producto con ID ($id)");
        }

        return $result[0];
    }

    /**
     * Guarda un nuevo producto en la base de datos
     *
     * @param array $data Datos del producto
     * @return void
     * @throws \Exception Si ya existe un producto con el mismo código
     */
    public function save(array $data): void
    {
        $this->validarCodigo(0, $data["codigo"]);
        $sql = "INSERT INTO {$this->table} VALUES (DEFAULT, :nombre, :codigo, :descripcion, :categoriaId, :precio, :stock)";
        $this->insertQuery($sql, $data);
    }

    /**
     * Actualiza los datos de un producto existente
     *
     * @param array $data Datos del producto, debe incluir el ID
     * @return void
     * @throws \Exception Si ya existe otro producto con el mismo código
     */
    public function update(array $data): void
    {
        $this->validarCodigo($data["id"], $data["codigo"]);
        $sql = "UPDATE {$this->table} 
                SET nombre = :nombre, codigo = :codigo, descripcion = :descripcion,
                    categoriaId = :categoriaId, precio = :precio, stock = :stock
                WHERE id = :id";
        $this->updateQuery($sql, $data);
    }

    /**
     * Elimina un producto por su ID
     *
     * @param int $id ID del producto
     * @return void
     */
    public function delete(int $id): void
    {
        $this->deleteQuery($id);
    }

    /**
     * Lista productos con filtros opcionales
     *
     * @param array $filters Filtros disponibles: nombre, categoriaId, codigo, limit, offset
     * @return array Lista de productos filtrados
     */
    public function list(array $filters): array
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS id, nombre, codigo, descripcion, categoriaId, precio, stock 
                FROM {$this->table}";
        $where = [];
        $params = [];

        if (!empty($filters["nombre"])) {
            $where[] = "nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        if (!empty($filters["codigo"])) {
            $where[] = "codigo LIKE :codigo";
            $params["codigo"] = "%" . $filters["codigo"] . "%";
        }

        if (!empty($filters["categoriaId"])) {
            $where[] = "categoriaId = :categoriaId";
            $params["categoriaId"] = $filters["categoriaId"];
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
     * Devuelve el número total de filas encontradas sin límite (paginación)
     *
     * @return int Total de registros encontrados
     */
    public function foundRows(): int
    {
        return $this->getFoundRows();
    }

    /**
     * Devuelve el último ID insertado (usado después de un save)
     *
     * @return int Último ID insertado
     */
    public function getLastInserId(): int
    {
        return (int) $this->connection->lastInsertId();
    }

    /**
     * Valida que no exista otro producto con el mismo código
     *
     * @param int $id ID actual (0 si es nuevo)
     * @param string $codigo Código a verificar
     * @return void
     * @throws \Exception Si ya existe un producto con ese código
     */
    private function validarCodigo(int $id, string $codigo): void
    {
        $sql = "SELECT COUNT(*) AS cantidad FROM {$this->table} WHERE codigo = :codigo AND id != :id";
        $params = [
            "codigo" => $codigo,
            "id" => $id
        ];

        $result = $this->selectQuery($sql, $params);
        if ($result[0]["cantidad"] > 0) {
            throw new \Exception("Ya existe un producto con el código '{$codigo}'");
        }
    }
}
