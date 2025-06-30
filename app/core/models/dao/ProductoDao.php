<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;
use app\core\models\dto\ProductoDto;

final class ProductoDao extends BaseDao implements InterfaceDao{

    public function __construct(\PDO $connection){
        parent::__construct($connection, "productos");
    }

    public function load(int $id): array{
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("Producto no encontrado con ID = {$id}");
        }
        return (new ProductoDto($data))->toArray();
    }

    public function save(array $data): void{
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

    public function update(array $data): void{
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

    public function delete(int $id): void{
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    public function list(array $filters): array{
        $where = [];
        $params = [];

        if (isset($filters["nombre"])) {
            $where[] = "nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        if (isset($filters["categoriaId"])) {
            $where[] = "categoriaId = :categoriaId";
            $params["categoriaId"] = $filters["categoriaId"];
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table}";

        if (count($where) > 0) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY nombre";

        if (isset($filters["limit"]) && isset($filters["offset"])) {
            $sql .= " LIMIT {$filters["offset"]}, {$filters["limit"]}";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        $result = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = (new ProductoDto($row))->toArray();
        }

        return $result;
    }

    public function suggestive(array $filters): array{
        $sql = "SELECT id, nombre FROM {$this->table} WHERE nombre LIKE :keyword ORDER BY nombre ASC LIMIT 10";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "keyword" => "%" . ($filters["keyword"] ?? "") . "%"
        ]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Verifica si ya existe un producto con el mismo código
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

    // Verifica si ya existe un producto con el mismo nombre en la misma categoría
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
