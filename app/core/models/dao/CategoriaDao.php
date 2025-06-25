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
            throw new \Exception("No se encontraron coincidencias para el identificador de la Categoria ({$id})");
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function save(array $data): void{
        $sql = "INSERT INTO {$this->table} VALUES(DEFAULT, :nombre)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);
    }

    public function update(array $data): void{

    }

    public function delete(int $id): void{

    }

    public function list(array $filters): array{
        return [];
    }

    public function suggestive(array $filters): array{
        return [];
    }

    public function foundRows(): int{
        return 0;
    }

    public function getLastInsertId(): int{
        return 0;
    }

}