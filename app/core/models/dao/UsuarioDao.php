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
        try {
            error_log("Datos recibidos en UsuarioDao::update: " . print_r($data, true));

            // Validar ID
            if (empty($data['id']) || !is_numeric($data['id'])) {
                throw new \Exception("ID de usuario inválido o no especificado.");
            }

            // Validar unicidad solo si los campos están presentes
            if (!empty($data["cuenta"]) && $this->existsByCuenta($data["cuenta"], $data["id"])) {
                throw new \Exception("La cuenta '{$data["cuenta"]}' ya está en uso por otro usuario.");
            }

            if (!empty($data["correo"]) && $this->existsByCorreo($data["correo"], $data["id"])) {
                throw new \Exception("El correo '{$data["correo"]}' ya está en uso por otro usuario.");
            }

            // Construir la consulta dinámicamente
            $fields = [];
            $params = [];
            foreach (['apellido', 'nombres', 'cuenta', 'perfil', 'clave', 'correo', 'estado', 'resetPass'] as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = :$field";
                    $params[$field] = $data[$field];
                }
            }
            $params['id'] = $data['id'];

            if (empty($fields)) {
                error_log("No hay campos para actualizar en UsuarioDao::update");
                return; // No hay nada que actualizar
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
            error_log("Consulta SQL en update: $sql");

            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta SQL");
            }

            $result = $stmt->execute($params);
            if (!$result) {
                throw new \Exception("Error al ejecutar la consulta SQL");
            }

            $rowsAffected = $stmt->rowCount();
            error_log("Filas afectadas en update: $rowsAffected");
            if ($rowsAffected === 0) {
                throw new \Exception("No se actualizó ningún usuario. Verifica el ID o los datos enviados.");
            }
        } catch (\Exception $e) {
            error_log("Error en UsuarioDao::update: " . $e->getMessage());
            throw $e;
        }
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

        if (isset($filters["correo"]) && trim($filters["correo"]) !== '') {
            $sql .= " AND correo LIKE :correo";
        }

        if (!empty($filters["limit"])) {
            $limit = (int)$filters["limit"];
            if ($limit > 0) {
                $sql .= " LIMIT {$limit}";
            }
        }

        //$sql .= " ORDER BY apellido, nombres";

        


        $stmt = $this->connection->prepare($sql);

        // Vinculaciones dinámicas
        if (!empty($filters["perfil"])) {
            $stmt->bindValue(":perfil", $filters["perfil"]);
        }

        if (!empty($filters["estado"])) {
            $stmt->bindValue(":estado", $filters["estado"], \PDO::PARAM_INT);
        }


        if (isset($filters["correo"]) && trim($filters["correo"]) !== '') {
            $stmt->bindValue(":correo", "%" . $filters["correo"] . "%");
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
    
     public function login($cuenta): array{
        $sql = "SELECT id, apellido, nombres, cuenta, clave, perfil, estado, resetPass";
        $sql .= " FROM usuarios";
        $sql .= " WHERE (cuenta = :cuenta OR correo = :cuenta)";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["cuenta" => $cuenta]);
        if($stmt->rowCount() != 1){
            throw new \Exception("El nombre de usuario o la contraseña no coinciden");
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updatePassword(int $userId, string $newPassword): void {
        $sql = "UPDATE usuarios SET clave = :clave, resetPass = 0 WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "clave" => password_hash($newPassword, PASSWORD_DEFAULT),
            "id" => $userId
        ]);
    }

  


}
