<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;

/**
 * DAO para la entidad Usuario.
 * Maneja operaciones CRUD y consultas adicionales sobre la tabla `usuarios`.
 */
final class UsuarioDao extends BaseDao implements InterfaceDao
{
    /**
     * Constructor que inicializa la conexión y la tabla.
     *
     * @param \PDO $connection Conexión PDO a la base de datos.
     */
    public function __construct(\PDO $connection)
    {
        parent::__construct($connection, "usuarios");
    }

    /**
     * Carga un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array Datos del usuario.
     * @throws \Exception Si no se encuentra el usuario.
     */
    public function load(int $id): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception("No se encontró el usuario con ID {$id}");
        }

        return $data;
    }

    /**
     * Guarda un nuevo usuario.
     *
     * @param array $data Datos del nuevo usuario.
     * @throws \Exception Si la cuenta o correo ya existen.
     */
    public function save(array $data): void
    {
        if ($this->existsByCuenta($data["cuenta"])) {
            throw new \Exception("La cuenta '{$data["cuenta"]}' ya está en uso.");
        }

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

    /**
     * Actualiza los datos de un usuario existente.
     *
     * @param array $data Datos actualizados del usuario.
     * @throws \Exception Si ocurren errores en la validación o ejecución.
     */
    public function update(array $data): void
    {
        try {
            error_log("Datos recibidos en UsuarioDao::update: " . print_r($data, true));

            if (empty($data['id']) || !is_numeric($data['id'])) {
                throw new \Exception("ID de usuario inválido o no especificado.");
            }

            if (!empty($data["cuenta"]) && $this->existsByCuenta($data["cuenta"], $data["id"])) {
                throw new \Exception("La cuenta '{$data["cuenta"]}' ya está en uso por otro usuario.");
            }

            if (!empty($data["correo"]) && $this->existsByCorreo($data["correo"], $data["id"])) {
                throw new \Exception("El correo '{$data["correo"]}' ya está en uso por otro usuario.");
            }

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
                return;
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
            error_log("Consulta SQL en update: $sql");

            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Error al preparar la consulta SQL");
            }

            if (!$stmt->execute($params)) {
                throw new \Exception("Error al ejecutar la consulta SQL");
            }

            error_log("Filas afectadas en update: " . $stmt->rowCount());
        } catch (\Exception $e) {
            error_log("Error en UsuarioDao::update: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina un usuario por ID.
     *
     * @param int $id ID del usuario.
     */
    public function delete(int $id): void
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Lista usuarios filtrados por parámetros opcionales.
     *
     * @param array $filters Filtros como perfil, estado, correo, limit.
     * @return array Lista de usuarios.
     */
    public function list(array $filters): array
    {
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

        $stmt = $this->connection->prepare($sql);

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

    /**
     * Sugerencias de usuarios por coincidencias parciales.
     *
     * @param array $filters Filtros, espera 'keyword'.
     * @return array Resultados sugeridos.
     */
    public function suggestive(array $filters): array
    {
        $sql = "SELECT id, cuenta, apellido, nombres FROM {$this->table} 
                WHERE cuenta LIKE :keyword OR apellido LIKE :keyword OR nombres LIKE :keyword 
                ORDER BY apellido, nombres LIMIT 10";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "keyword" => "%" . ($filters["keyword"] ?? "") . "%"
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna el total de filas encontradas tras la última consulta con SQL_CALC_FOUND_ROWS.
     *
     * @return int Total de filas.
     */
    public function foundRows(): int
    {
        return parent::foundRows();
    }

    /**
     * Obtiene el ID del último registro insertado.
     *
     * @return int Último ID insertado.
     */
    public function getLastInsertId(): int
    {
        return parent::getLastInsertId();
    }

    /**
     * Habilita un usuario.
     *
     * @param int $id ID del usuario.
     */
    public function enable(int $id): void
    {
        $sql = "UPDATE {$this->table} SET estado = 1 WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Deshabilita un usuario.
     *
     * @param int $id ID del usuario.
     */
    public function disable(int $id): void
    {
        $sql = "UPDATE {$this->table} SET estado = 0 WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Marca el usuario para reiniciar la contraseña.
     *
     * @param int $id ID del usuario.
     */
    public function reset(int $id): void
    {
        $sql = "UPDATE {$this->table} SET resetPass = 1 WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["id" => $id]);
    }

    /**
     * Verifica si existe una cuenta, excluyendo un ID opcional.
     *
     * @param string $cuenta Cuenta a verificar.
     * @param int $excludeId ID a excluir.
     * @return bool Verdadero si existe.
     */
    public function existsByCuenta(string $cuenta, int $excludeId = 0): bool
    {
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

    /**
     * Verifica si existe un correo, excluyendo un ID opcional.
     *
     * @param string $correo Correo a verificar.
     * @param int $excludeId ID a excluir.
     * @return bool Verdadero si existe.
     */
    public function existsByCorreo(string $correo, int $excludeId = 0): bool
    {
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

    /**
     * Busca un usuario por su cuenta.
     *
     * @param string $cuenta Cuenta del usuario.
     * @return array|null Datos del usuario o null si no existe.
     */
    public function findByCuenta(string $cuenta): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE cuenta = :cuenta LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":cuenta", $cuenta);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Busca usuario por cuenta o correo para login.
     *
     * @param string $cuenta Cuenta o correo.
     * @return array Datos del usuario.
     * @throws \Exception Si no se encuentra.
     */
    public function login($cuenta): array
    {
        $sql = "SELECT id, apellido, nombres, cuenta, clave, perfil, estado, resetPass
                FROM usuarios
                WHERE (cuenta = :cuenta OR correo = :cuenta)";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(["cuenta" => $cuenta]);

        if ($stmt->rowCount() != 1) {
            throw new \Exception("El nombre de usuario o la contraseña no coinciden");
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param int $userId ID del usuario.
     * @param string $newPassword Nueva contraseña.
     */
    public function updatePassword(int $userId, string $newPassword): void
    {
        $sql = "UPDATE usuarios SET clave = :clave, resetPass = 0 WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            "clave" => password_hash($newPassword, PASSWORD_DEFAULT),
            "id" => $userId
        ]);
    }

    /**
     * Busca un usuario por su ID.
     *
     * @param int $id ID del usuario.
     * @return array|null Datos del usuario o null si no existe.
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
