<?php

namespace app\core\models\dao;

use app\core\models\dao\base\BaseDao;
use app\core\models\dao\base\InterfaceDao;

/**
 * DAO para la entidad Usuario.
 * Maneja operaciones de base de datos sobre la tabla 'usuarios'.
 */
final class UsusarioDao extends BaseDao implements InterfaceDao{
    /**
     * Constructor
     *
     * @param \PDO|null $connection Conexión a la base de datos
     */
    public function __construct(?\PDO $connection)
    {
        parent::__construct($connection, 'usuarios');
    }

    /**
     * Carga un usuario por ID
     *
     * @param int $id ID del usuario
     * @return array Datos del usuario
     * @throws \Exception Si no se encuentra el usuario
     */
    public function load(int $id): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->selectQuery($sql, ["id" => $id]);

        if (empty($result)) {
            throw new \Exception("No se encontró el usuario con ID ($id)");
        }

        return $result[0];
    }

    /**
     * Guarda un nuevo usuario
     *
     * @param array $data Datos del nuevo usuario
     * @return void
     */
    public function save(array $data): void
    {
        $sql = "INSERT INTO {$this->table}
                (apellido, nombres, cuenta, perfil, clave, correo, estado, fechaAlta, resetPass)
                VALUES
                (:apellido, :nombres, :cuenta, :perfil, :clave, :correo, :estado, :fechaAlta, :resetPass)";
        $this->insertQuery($sql, $data);
    }

    /**
     * Actualiza un usuario existente
     *
     * @param array $data Datos del usuario (incluye ID)
     * @return void
     */
    public function update(array $data): void
    {
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
        $this->updateQuery($sql, $data);
    }

    /**
     * Elimina un usuario por ID
     *
     * @param int $id ID del usuario
     * @return void
     */
    public function delete(int $id): void
    {
        $this->deleteQuery($id);
    }

    /**
     * Lista usuarios según filtros
     *
     * @param array $filters Filtros disponibles: cuenta, apellido, perfil, correo, estado
     * @return array Lista de usuarios
     */
    public function list(array $filters): array
    {
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table}";
        $where = [];
        $params = [];

        if (!empty($filters["cuenta"])) {
            $where[] = "cuenta LIKE :cuenta";
            $params["cuenta"] = "%" . $filters["cuenta"] . "%";
        }

        if (!empty($filters["apellido"])) {
            $where[] = "apellido LIKE :apellido";
            $params["apellido"] = "%" . $filters["apellido"] . "%";
        }

        if (!empty($filters["perfil"])) {
            $where[] = "perfil = :perfil";
            $params["perfil"] = $filters["perfil"];
        }

        if (!empty($filters["correo"])) {
            $where[] = "correo LIKE :correo";
            $params["correo"] = "%" . $filters["correo"] . "%";
        }

        if (isset($filters["estado"])) {
            $where[] = "estado = :estado";
            $params["estado"] = $filters["estado"];
        }

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY apellido, nombres";

        if (isset($filters["limit"])) {
            $sql .= " LIMIT " . (int)$filters["limit"];
        }

        if (isset($filters["offset"])) {
            $sql .= " OFFSET " . (int)$filters["offset"];
        }

        return $this->selectQuery($sql, $params);
    }

    /**
     * Devuelve la cantidad total de registros filtrados (para paginación)
     *
     * @return int
     */
    public function foundRows(): int
    {
        return $this->getFoundRows();
    }

    /**
     * Devuelve el último ID insertado
     *
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int)$this->connection->lastInsertId();
    }

    /**
     * Habilita a un usuario (estado = 1)
     *
     * @param int $id ID del usuario
     * @return void
     */
    public function enable(int $id): void
    {
        $sql = "UPDATE {$this->table} SET estado = 1 WHERE id = :id";
        $this->updateQuery($sql, ["id" => $id]);
    }

    /**
     * Deshabilita a un usuario (estado = 0)
     *
     * @param int $id ID del usuario
     * @return void
     */
    public function disable(int $id): void
    {
        $sql = "UPDATE {$this->table} SET estado = 0 WHERE id = :id";
        $this->updateQuery($sql, ["id" => $id]);
    }

    /**
     * Marca al usuario para reiniciar su contraseña (resetPass = 1)
     *
     * @param int $id ID del usuario
     * @return void
     */
    public function reset(int $id): void
    {
        $sql = "UPDATE {$this->table} SET resetPass = 1 WHERE id = :id";
        $this->updateQuery($sql, ["id" => $id]);
    }
}
