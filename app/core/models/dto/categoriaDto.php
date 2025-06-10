<?php

namespace app\core\model\dto;

use app\core\model\dto\base\InterfaceDto;

final class CategoriaDto implements InterfaceDto{

    private $id, $nombre;

    public function __construct(array $data = [])
    {
        //Operador coalescente null
        $this->setId($data["id"] ?? 0);
        $this->setNombre($data["nombre"] ?? "");
    }

    public function getId(): int{
        return $this->id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function setId(int $id): void{
        $this->id = $id > 0 ? $id : 0;
    }

    public function setNombre(string $nombre): void{
    $this->nombre = $nombre;
    }

    public function toArray(): array{
        return [
            "id"        => $this->getId(),
            "nombre"    => $this->getNombre()
        ];
    }

}