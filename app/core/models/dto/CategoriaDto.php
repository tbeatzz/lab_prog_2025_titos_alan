<?php

namespace app\core\models\dto;

use app\core\models\dto\base\InterfaceDto;

/**
 * DTO para la entidad Categoría.
 * Encapsula los datos y validaciones básicas.
 */
final class CategoriaDto implements InterfaceDto{

    private $id, $nombre;

    /**
     * Constructor que permite cargar los datos desde un arreglo asociativo.
     *
     * @param array $data Datos iniciales del DTO.
     */
    public function __construct(array $data = [])
    {
        $this->setId($data["id"] ?? 0);
        $this->setNombre($data["nombre"] ?? "");
    }

    /**
     * Obtiene el ID de la categoría.
     *
     * @return int
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * Obtiene el nombre de la categoría.
     *
     * @return string
     */
    public function getNombre(): string{
        return $this->nombre;
    }

    /**
     * Establece el ID de la categoría (debe ser > 0).
     *
     * @param int $id
     */
    public function setId(int $id): void{
        $this->id = $id > 0 ? $id : 0;
    }

    /**
     * Establece el nombre de la categoría, con validación de longitud <= 100 caracteres.
     *
     * @param string $nombre
     */
    public function setNombre(string $nombre): void{
        $this->nombre = (strlen(trim($nombre)) <= 100) ? trim($nombre) : "";
    }

    /**
     * Devuelve los datos del DTO en forma de arreglo.
     *
     * @return array
     */
    public function toArray(): array{
        return [
            "id"        => $this->getId(),
            "nombre"    => $this->getNombre()
        ];
    }
}
