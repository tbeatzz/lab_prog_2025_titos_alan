<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;
use app\core\models\dto\CategoriaDto;

/**
 * DAO para manejar operaciones de base de datos de la entidad Categoría.
 * Implementa operaciones CRUD y listados con filtros.
 */
final class CategoriaDao extends BaseDao implements InterfaceDao {

    /**
     * Constructor del DAO de Categoría
     *
     * @param \PDO $connection Conexión activa a la base de datos
     */
    public function __construct(\PDO $connection) {
        parent::__construct($connection, "categorias");
    }

    /**
     * Carga una categoría por su ID
     *
     * @param int $id Identificador único de la categoría
     * @return array Datos de la categoría
     * @throws \Exception Si no se encuentra la categoría
     */
    public function load(int $id): array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            throw new \Exception("Categoría con ID {$id} no encontrada.");
        }

        return (new CategoriaDto($data))->toArray();
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     *
     * @param array $data Datos a insertar
     * @throws \Exception Si ya existe una categoría con el mismo nombre
     */
    public function save(array $data): void {
        unset($data["id"]);

        if ($this->validarNombre($data["nombre"])) {
            throw new \Exception("<p>El nombre <strong>{$data['nombre']}</strong> ya está siendo utilizado.</p>");
        }

        $sql = "INSERT INTO {$this->table} VALUES(DEFAULT, :nombre)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
    }

    /**
     * Actualiza los datos de una categoría existente
     *
     * @param array $data Datos con ID y nombre actualizados
     * @throws \Exception Si otra categoría ya tiene ese nombre
     */
    public function update(array $data): void {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nombre = :nombre AND id != :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "nombre" => $data["nombre"],
            "id"     => $data["id"]
        ]);

        if ((int) $stmt->fetchColumn() > 0) {
            throw new \Exception("<p>El nombre <strong>{$data['nombre']}</strong> ya está siendo utilizado por otra categoría.</p>");
        }

        $sql = "UPDATE {$this->table} SET nombre = :nombre WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "id"     => $data["id"],
            "nombre" => $data["nombre"]
        ]);
    }

    /**
     * Elimina una categoría por su ID
     *
     * @param int $id ID de la categoría a eliminar
     */
    public function delete(int $id): void {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Lista categorías con filtros opcionales
     *
     * @param array $filters Filtros disponibles: nombre, limit, offset
     * @return array Lista de categorías encontradas
     */
    public function list(array $filters): array {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters["nombre"])) {
            $sql .= " AND nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        $sql .= " ORDER BY nombre ASC";

        if (isset($filters["limit"], $filters["offset"])) {
            $sql .= " LIMIT :offset, :limit";
            $params["offset"] = (int) $filters["offset"];
            $params["limit"] = (int) $filters["limit"];
        }

        $stmt = $this->connection->prepare($sql);

        if (isset($params["offset"])) {
            $stmt->bindValue(":offset", $params["offset"], \PDO::PARAM_INT);
            $stmt->bindValue(":limit", $params["limit"], \PDO::PARAM_INT);
            unset($params["offset"], $params["limit"]);
        }

        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Lista de sugerencias según un filtro por palabra clave
     *
     * @param array $filters Debe incluir 'keyword'
     * @return array Resultados coincidentes
     */
    public function suggestive(array $filters): array {
        $sql = "SELECT * FROM {$this->table} WHERE nombre LIKE :keyword ORDER BY nombre ASC LIMIT 10";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "keyword" => "%" . ($filters["keyword"] ?? "") . "%"
        ]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si existe una categoría con el nombre dado
     *
     * @param string $nombre Nombre a verificar
     * @return bool true si existe, false si no
     */
    public function validarNombre(string $nombre): bool {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nombre = :nombre";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["nombre" => $nombre]);
        return (bool) $stmt->fetchColumn();
    }
}
