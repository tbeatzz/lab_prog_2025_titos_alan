<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;

/**
 * DAO para la entidad Categoría.
 */
final class CategoriaDao extends BaseDao implements InterfaceDao {

    public function __construct(\PDO $connection) {
        parent::__construct($connection, "categorias");
    }

    /**
     * Carga una categoría por ID.
     */
    public function load(int $id): array {
        $sql = "SELECT id, nombre FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);

        if ($stmt->rowCount() == 0) {
            throw new \Exception("No se encontraron coincidencias para el identificador de la Categoría ({$id})");
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Guarda una nueva categoría.
     */
    public function save(array $data): void {
        $this->validarNombre(0, $data["nombre"]);

        $sql = "INSERT INTO {$this->table} (nombre) VALUES (:nombre)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["nombre" => $data["nombre"]]);
    }

    /**
     * Actualiza una categoría existente.
     */
    public function update(array $data): void {
        $this->validarNombre($data["id"], $data["nombre"]);

        $sql = "UPDATE {$this->table} SET nombre = :nombre WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "nombre" => $data["nombre"],
            "id" => $data["id"]
        ]);
    }

    /**
     * Elimina una categoría por ID.
     */
    public function delete(int $id): void {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Lista categorías con filtros opcionales.
     */
    public function list(array $filters): array {
        $sql = "SELECT SQL_CALC_FOUND_ROWS id, nombre FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters["nombre"])) {
            $sql .= " AND nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        if (isset($filters["startIndex"], $filters["offset"])) {
            $sql .= " LIMIT :startIndex, :offset";
            $params["startIndex"] = (int)$filters["startIndex"];
            $params["offset"] = (int)$filters["offset"];
        }

        $stmt = $this->connection->prepare($sql);

        // Necesitamos bindValue para LIMIT y OFFSET si existen
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve la cantidad total de resultados de la última consulta con SQL_CALC_FOUND_ROWS.
     */
    public function foundRows(): int {
        $stmt = $this->connection->query("SELECT FOUND_ROWS()");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Sugerencias rápidas (por implementar si es necesario).
     */
    public function suggestive(array $filters): array {
        return [];
    }

    /**
     * Último ID insertado.
     */
    public function getLastInsertId(): int {
        return (int) $this->connection->lastInsertId();
    }

    /**
     * Valida que no exista otra categoría con el mismo nombre.
     */
    private function validarNombre(int $id, string $nombre): void {
        $sql = "SELECT COUNT(id) AS total FROM {$this->table} WHERE nombre = :nombre AND id <> :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "nombre" => $nombre,
            "id" => $id
        ]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ((int)$result["total"] > 0) {
            throw new \Exception("Ya existe una categoría con el nombre <strong>{$nombre}</strong>.");
        }
    }
}
