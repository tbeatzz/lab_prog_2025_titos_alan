<?php

namespace app\core\model\dto;

use app\core\model\dto\base\InterfaceDto;

final class ProductoDto implements InterfaceDto {

    private $id, $nombre, $codigo, $descripcion, $categoriaId, $precio, $stock;

    public function __construct(array $data = []) {
        $this->setId($data["id"] ?? 0);
        $this->setNombre($data["nombre"] ?? "");
        $this->setCodigo($data["codigo"] ?? "");
        $this->setDescripcion($data["descripcion"] ?? "");
        $this->setCategoriaId($data["categoriaId"] ?? 0);
        $this->setPrecio($data["precio"] ?? 0);
        $this->setStock($data["stock"] ?? 0);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getCodigo(): string {
        return $this->codigo;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getCategoriaId(): int {
        return $this->categoriaId;
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function setId(int $id): void {
        $this->id = $id > 0 ? $id : 0;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setCodigo(string $codigo): void {
        $this->codigo = $codigo;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setCategoriaId(int $categoriaId): void {
        $this->categoriaId = $categoriaId > 0 ? $categoriaId : 0;
    }

    public function setPrecio(float $precio): void {
        $this->precio = $precio;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function toArray(): array {
        return [
            "id" => $this->getId(),
            "nombre" => $this->getNombre(),
            "codigo" => $this->getCodigo(),
            "descripcion" => $this->getDescripcion(),
            "categoriaId" => $this->getCategoriaId(),
            "precio" => $this->getPrecio(),
            "stock" => $this->getStock()
        ];
    }
}
