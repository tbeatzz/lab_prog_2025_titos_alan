<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;

final class CategoriaDao extends BaseDao implements InterfaceDao{

    public function __construct(\PDO $connection){
        parent::__construct($connection, "categorias");
    }

    public function load(int $id): array{
        $sql = "SELECT id, nombre FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
        if($stmt->rowCount() == 0){
            throw new \Exception("No se encontraron coincidencias para el identificador de la Marca ({$id})");
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    /**
     * Guarda una nueva categoría en la base de datos.
     * 
     * @param array $data
     * @return void
     */
    public function save(array $data): void {
        $this->validarNombre(0, $data["nombre"]);
        $sql = "INSERT INTO {$this->table} VALUES (DEFAULT, :nombre)";
        $this->insertQuery($sql, $data);
    }

    /**
     * Actualiza una categoría existente.
     * 
     * @param array $data
     * @return void
     */
    public function update(array $data): void {
        $this->validarNombre($data["id"], $data["nombre"]);
        $sql = "UPDATE {$this->table} SET nombre = :nombre WHERE id = :id";
        $this->updateQuery($sql, $data);
    }

    /**
     * Elimina una categoría por su ID.
     * 
     * @param int $id
     * @return void
     */
    public function delete(int $id): void {
        $this->deleteQuery($id);
    }

    /**
     * Lista todas las categorías, con soporte para filtros.
     * 
     * @param array $filters
     * @return array
     */
    public function list(array $filters): array {
        $sql = "SELECT SQL_CALC_FOUND_ROWS id, nombre FROM {$this->table} WHERE 1 = 1";
        $params = [];

        if (!empty($filters["nombre"])) {
            $sql .= " AND nombre LIKE :nombre";
            $params["nombre"] = "%" . $filters["nombre"] . "%";
        }

        if (isset($filters["startIndex"], $filters["offset"])) {
            $sql .= " LIMIT {$filters["startIndex"]}, {$filters["offset"]}";
        }

        return $this->selectQuery($sql, $params);
    }

    /**
     * Devuelve la cantidad total de resultados de la última consulta con SQL_CALC_FOUND_ROWS.
     * 
     * @return int
     */
    public function foundRows(): int {
        return $this->selectFoundRows();
    }

    /**
     * Devuelve el último ID insertado.
     * 
     * @return int
     */
    public function getLastInsertId(): int {
        return $this->connection->lastInsertId();
    }

    /**
     * Verifica que no exista otra categoría con el mismo nombre.
     * 
     * @param int $id
     * @param string $nombre
     * @throws \Exception si el nombre ya existe
     */
    private function validarNombre(int $id, string $nombre): void {
        $sql = "SELECT COUNT(id) AS total FROM {$this->table} WHERE nombre = :nombre AND id <> :id";
        $params = ["id" => $id, "nombre" => $nombre];
        $result = $this->selectQuery($sql, $params);

        if ((int)$result[0]["total"] > 0) {
            throw new \Exception("Ya existe una categoría con el nombre <strong>{$nombre}</strong>.");
        }
    }
}