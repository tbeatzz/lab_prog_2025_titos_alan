<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;

final class UsuarioDao extends BaseDao implements InterfaceDao {

    public function __construct(\PDO $connection) {
        parent::__construct($connection, "usuarios");
    }

    public function load(int $id): array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("No se encontró el usuario con ID {$id}");
        }

        return $data;
    }

    public function save(array $data): void {
        // Validar que no exista otra cuenta igual
        if ($this->existsByCuenta($data["cuenta"])) {
            throw new \Exception("La cuenta '{$data["cuenta"]}' ya está en uso.");
        }

        // Validar que no exista otro correo igual
        if ($this->existsByCorreo($data["correo"])) {
            throw new \Exception("El correo '{$data["correo"]}' ya está en uso.");
    }

        $sql = "INSERT INTO {$this->table} 
            (apellido, nombres, cuenta, perfil, clave, correo, estado, fechaAlta, resetPass) 
            VALUES (:apellido, :nombres, :cuenta, :perfil, :clave, :correo, :estado, :fechaAlta, :resetPass)";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "apellido" => $data["apellido"],
            "nombres" => $data["nombres"],
            "cuenta" => $data["cuenta"],
            "perfil" => $data["perfil"],
            "clave" => $data["clave"],
            "correo" => $data["correo"],
            "estado" => $data["estado"],
            "fechaAlta" => $data["fechaAlta"],
            "resetPass" => $data["resetPass"]
        ]);
    }

    public function update(array $data): void {
        // Validar que no exista otra cuenta igual para otro ID
        if ($this->existsByCuenta($data["cuenta"], $data["id"])) {
            throw new \Exception("La cuenta '{$data["cuenta"]}' ya está en uso por otro usuario.");
        }

        // Validar que no exista otro correo igual para otro ID
        if ($this->existsByCorreo($data["correo"], $data["id"])) {
            throw new \Exception("El correo '{$data["correo"]}' ya está en uso por otro usuario.");
        }

        $sql = "UPDATE {$this->table} SET 
            apellido = :apellido,
            nombres = :nombres,
            cuenta = :cuenta,
            perfil = :perfil,
            clave = :clave,
            correo = :correo,
            estado = :estado,
            fechaAlta = :fechaAlta,
            resetPass = :resetPass
            WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "apellido" => $data["apellido"],
            "nombres" => $data["nombres"],
            "cuenta" => $data["cuenta"],
            "perfil" => $data["perfil"],
            "clave" => $data["clave"],
            "correo" => $data["correo"],
            "estado" => $data["estado"],
            "fechaAlta" => $data["fechaAlta"],
            "resetPass" => $data["resetPass"],
            "id" => $data["id"]
        ]);
    }

    public function delete(int $id): void {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    public function list(array $filters): array {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} WHERE 1";

        if (!empty($filters["perfil"])) {
            $sql .= " AND perfil = :perfil";
        }

        if (!empty($filters["estado"])) {
            $sql .= " AND estado = :estado";
        }

        $sql .= " ORDER BY apellido, nombres";

        if (!empty($filters["limit"])) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->connection->prepare($sql);

        // Vinculaciones dinámicas
        if (!empty($filters["perfil"])) {
            $stmt->bindValue(":perfil", $filters["perfil"]);
        }

        if (!empty($filters["estado"])) {
            $stmt->bindValue(":estado", $filters["estado"], \PDO::PARAM_INT);
        }

        if (!empty($filters["limit"])) {
            $stmt->bindValue(":limit", (int)$filters["limit"], \PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function suggestive(array $filters): array {
        $sql = "SELECT id, cuenta, apellido, nombres FROM {$this->table} 
                WHERE cuenta LIKE :keyword OR apellido LIKE :keyword OR nombres LIKE :keyword 
                ORDER BY apellido, nombres LIMIT 10";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "keyword" => "%" . ($filters["keyword"] ?? "") . "%"
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function foundRows(): int {
        return parent::foundRows();
    }

    public function getLastInsertId(): int {
        return parent::getLastInsertId();
    }

    // ================== Métodos especiales ===================

    public function enable(int $id): void {
        $sql = "UPDATE {$this->table} SET estado = 1 WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    public function disable(int $id): void {
        $sql = "UPDATE {$this->table} SET estado = 0 WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    public function reset(int $id): void {
        $sql = "UPDATE {$this->table} SET resetPass = 1 WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    public function existsByCuenta(string $cuenta, int $excludeId = 0): bool {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE cuenta = :cuenta";
        if ($excludeId > 0) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":cuenta", $cuenta);
        if ($excludeId > 0) {
            $stmt->bindValue(":id", $excludeId, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function existsByCorreo(string $correo, int $excludeId = 0): bool {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE correo = :correo";
        if ($excludeId > 0) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":correo", $correo);
        if ($excludeId > 0) {
            $stmt->bindValue(":id", $excludeId, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function findByCuenta(string $cuenta): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE cuenta = :cuenta LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":cuenta", $cuenta);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ?: null;
    }

}
