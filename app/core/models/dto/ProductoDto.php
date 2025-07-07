<?php

namespace app\core\models\dto;

use app\core\models\dto\base\InterfaceDto;

/**
 * Clase DTO para la entidad Producto.
 * Contiene los atributos y validaciones del producto.
 */
final class ProductoDto implements InterfaceDto {

    private $id, $nombre, $codigo, $descripcion,$categoria, $categoriaId, $precio, $stock;

    /**
     * Constructor del DTO. Recibe datos opcionales y los valida.
     *
     * @param array $data Datos del producto.
     */
    public function __construct(array $data = []) {
        $this->id          = $data['id'] ?? null;
        $this->nombre      = $data['nombre'] ?? '';
        $this->codigo      = $data['codigo'] ?? '';
        $this->descripcion = $data['descripcion'] ?? '';
        $this->categoriaId = $data['categoriaId'] ?? null;
        $this->categoria   = $data['categoria'] ?? null; // 👈 ¡esto es clave!
        $this->precio      = $data['precio'] ?? 0.0;
        $this->stock       = $data['stock'] ?? 0;
    }


    /** Getters */
    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCodigo(): string { return $this->codigo; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getCategoriaId(): int { return $this->categoriaId; }
    public function getPrecio(): float { return $this->precio; }
    public function getStock(): int { return $this->stock; }

    /** Setters */
    public function setId(int $id): void {
        $this->id = $id > 0 ? $id : 0;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = (strlen(trim($nombre)) <= 100) ? trim($nombre) : "";
    }

    public function setCodigo(string $codigo): void {
        $this->codigo = (strlen(trim($codigo)) <= 25) ? trim($codigo) : "";
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = (strlen(trim($descripcion)) <= 255) ? trim($descripcion) : "";
    }

    public function setCategoriaId(int $categoriaId): void {
        $this->categoriaId = $categoriaId > 0 ? $categoriaId : 0;
    }

    public function setPrecio(float $precio): void {
        $this->precio = $precio >= 0 ? $precio : 0;
    }

    public function setStock(int $stock): void {
        $this->stock = $stock >= 0 ? $stock : 0;
    }

    /**
     * Convierte el objeto en un array asociativo.
     *
     * @return array
     */
    public function toArray(): array {
        return [
            "id"          => $this->id,
            "nombre"      => $this->nombre,
            "codigo"      => $this->codigo,
            "descripcion" => $this->descripcion,
            "categoriaId" => $this->categoriaId,
            "categoria"   => $this->categoria, 
            "precio"      => $this->precio,
            "stock"       => $this->stock
        ];
    }

}
