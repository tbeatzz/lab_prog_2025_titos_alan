<?php

use PHPUnit\Framework\TestCase;
use app\core\models\dao\ProductoDao;

final class ProductoDaoTest extends TestCase
{
    private ProductoDao $dao;

    protected function setUp(): void
    {
        $pdo = new PDO("mysql:host=localhost;dbname=test_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dao = new ProductoDao($pdo);
    }

    public function testSaveAndLoadProducto(): void
    {
        $data = [
            "nombre" => "Teclado",
            "codigo" => "TEC123",
            "descripcion" => "Teclado mecánico",
            "categoriaId" => 1,
            "precio" => 1500.00,
            "stock" => 10
        ];
        $this->dao->save($data);

        $id = $this->dao->getLastInsertId();
        $producto = $this->dao->load($id);

        $this->assertEquals("Teclado", $producto["nombre"]);
    }

    public function testUpdateProducto(): void
    {
        $this->dao->save([
            "nombre" => "Mouse",
            "codigo" => "MOU456",
            "descripcion" => "Mouse óptico",
            "categoriaId" => 1,
            "precio" => 900.00,
            "stock" => 20
        ]);
        $id = $this->dao->getLastInsertId();

        $this->dao->update([
            "id" => $id,
            "nombre" => "Mouse inalámbrico",
            "codigo" => "MOU789",
            "descripcion" => "Mouse sin cable",
            "categoriaId" => 1,
            "precio" => 1200.00,
            "stock" => 15
        ]);

        $producto = $this->dao->load($id);
        $this->assertEquals("Mouse inalámbrico", $producto["nombre"]);
    }

    public function testDeleteProducto(): void
    {
        $this->dao->save([
            "nombre" => "Eliminar",
            "codigo" => "DEL999",
            "descripcion" => "",
            "categoriaId" => 1,
            "precio" => 0,
            "stock" => 0
        ]);
        $id = $this->dao->getLastInsertId();

        $this->dao->delete($id);
        $this->expectException(Exception::class);
        $this->dao->load($id);
    }
}
