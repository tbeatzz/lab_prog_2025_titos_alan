<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;
use app\core\models\dto\ProductoDto;

/**
 * DAO para la entidad Producto.
 * Maneja el acceso a datos de la tabla `productos`.
 */
final class ProductoDao extends BaseDao implements InterfaceDao {

    public function __construct(\PDO $connection) {
        parent::__construct($connection, "productos");
    }

    /**
     * Carga un producto por ID.
     *
     * @param int $id
     * @return array
     * @throws \Exception si no se encuentra el producto.
     */
    public function load(int $id): array {
        $sql = "SELECT p.*, c.nombre AS categoria
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.categoriaId = c.id
                WHERE p.id = :id LIMIT 1";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("Producto no encontrado con ID = {$id}");
        }
        return (new ProductoDto($data))->toArray();
    }


    /**
     * Guarda un nuevo producto.
     *
     * @param array $data
     * @throws \Exception si el código o nombre ya están en uso.
     */
    public function save(array $data): void {
        if ($this->existsByCodigo($data["codigo"])) {
            throw new \Exception("El código '{$data["codigo"]}' ya está siendo utilizado.");
        }

        if ($this->existsByNombreYCategoria($data["nombre"], (int)$data["categoriaId"])) {
            throw new \Exception("El producto '{$data["nombre"]}' ya existe en esta categoría.");
        }

        $sql = "INSERT INTO {$this->table} (nombre, codigo, descripcion, categoriaId, precio, stock) 
                VALUES (:nombre, :codigo, :descripcion, :categoriaId, :precio, :stock)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "nombre"      => $data["nombre"],
            "codigo"      => $data["codigo"],
            "descripcion" => $data["descripcion"],
            "categoriaId" => $data["categoriaId"],
            "precio"      => $data["precio"],
            "stock"       => $data["stock"]
        ]);
    }

    /**
     * Actualiza un producto existente.
     *
     * @param array $data
     * @throws \Exception si el código o nombre ya están en uso por otro producto.
     */
    public function update(array $data): void {
        $id = (int)$data["id"];

        if ($this->existsByCodigo($data["codigo"], $id)) {
            throw new \Exception("El código '{$data["codigo"]}' ya está siendo utilizado por otro producto.");
        }

        if ($this->existsByNombreYCategoria($data["nombre"], (int)$data["categoriaId"], $id)) {
            throw new \Exception("El producto '{$data["nombre"]}' ya existe en esta categoría.");
        }

        $sql = "UPDATE {$this->table} 
                SET nombre = :nombre, codigo = :codigo, descripcion = :descripcion, 
                    categoriaId = :categoriaId, precio = :precio, stock = :stock
                WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "id"          => $id,
            "nombre"      => $data["nombre"],
            "codigo"      => $data["codigo"],
            "descripcion" => $data["descripcion"],
            "categoriaId" => $data["categoriaId"],
            "precio"      => $data["precio"],
            "stock"       => $data["stock"]
        ]);
    }

    /**
     * Elimina un producto por ID.
     *
     * @param int $id
     */
    public function delete(int $id): void {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Lista productos según filtros.
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters): array {
        $where = [];
        $params = [];

        // Filtros condicionales
        if (isset($filters["nombre"])) {
            $where[] = "p.nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        if (isset($filters["codigo"])) {
            $where[] = "p.codigo LIKE :codigo";
            $params["codigo"] = "%" . $filters["codigo"] . "%";
        }

        if (isset($filters["categoriaId"])) {
            $where[] = "p.categoriaId = :categoriaId";
            $params["categoriaId"] = $filters["categoriaId"];
        }

        // Base SQL
        $sql = "SELECT SQL_CALC_FOUND_ROWS p.*, c.nombre AS categoria
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.categoriaId = c.id";

        // Condiciones WHERE
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        // Orden dinámico (por seguridad, validamos campos permitidos)
        $ordenValido = [
            "nombre_asc"  => "p.nombre ASC",
            "nombre_desc" => "p.nombre DESC",
            "precio_asc"  => "p.precio ASC",
            "precio_desc" => "p.precio DESC",
            "stock_asc"   => "p.stock ASC",
            "stock_desc"  => "p.stock DESC"
        ];

        $sql .= " ORDER BY ";
        if (isset($filters["orden"]) && isset($ordenValido[$filters["orden"]])) {
            $sql .= $ordenValido[$filters["orden"]];
        } else {
            $sql .= "p.nombre ASC"; // valor por defecto
        }

        // Limit y offset (paginación)
        if (isset($filters["limit"]) && isset($filters["offset"])) {
            $sql .= " LIMIT :offset, :limit";
            $params["offset"] = (int) $filters["offset"];
            $params["limit"] = (int) $filters["limit"];
        }

        // Preparar y ejecutar
        $stmt = $this->connection->prepare($sql);

        foreach ($params as $key => $value) {
            if (in_array($key, ["offset", "limit", "categoriaId"])) {
                $stmt->bindValue(":$key", $value, \PDO::PARAM_INT);
            } else {
                $stmt->bindValue(":$key", $value, \PDO::PARAM_STR);
            }
        }

        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = (new ProductoDto($row))->toArray();
        }

        return $result;
    }


    /**
     * Devuelve una lista de sugerencias de productos por nombre.
     *
     * @param array $filters
     * @return array
     */
    public function suggestive(array $filters): array {
        $sql = "SELECT id, nombre FROM {$this->table} WHERE nombre LIKE :keyword ORDER BY nombre ASC LIMIT 10";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "keyword" => "%" . ($filters["keyword"] ?? "") . "%"
        ]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si existe un producto con un código.
     *
     * @param string $codigo
     * @param int $excludeId (opcional) ID a excluir
     * @return bool
     */
    private function existsByCodigo(string $codigo, int $excludeId = 0): bool {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE codigo = :codigo";
        if ($excludeId > 0) {
            $sql .= " AND id != :id";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":codigo", $codigo);
        if ($excludeId > 0) {
            $stmt->bindValue(":id", $excludeId, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Verifica si existe un producto con el mismo nombre en la misma categoría.
     *
     * @param string $nombre
     * @param int $categoriaId
     * @param int $excludeId (opcional)
     * @return bool
     */
    private function existsByNombreYCategoria(string $nombre, int $categoriaId, int $excludeId = 0): bool {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nombre = :nombre AND categoriaId = :categoriaId";
        if ($excludeId > 0) {
            $sql .= " AND id != :id";
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":nombre", $nombre);
        $stmt->bindValue(":categoriaId", $categoriaId, \PDO::PARAM_INT);
        if ($excludeId > 0) {
            $stmt->bindValue(":id", $excludeId, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }
}
