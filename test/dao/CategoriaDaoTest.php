<?php

use PHPUnit\Framework\TestCase;
use app\core\models\dao\CategoriaDao;
use app\core\model\dto\CategoriaDto;

final class CategoriaDaoTest extends TestCase
{
    private CategoriaDao $dao;

    protected function setUp(): void
    {
        $pdo = new PDO("mysql:host=localhost;dbname=test_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dao = new CategoriaDao($pdo);
    }

    public function testSaveAndLoadCategoria(): void
    {
        $data = [
            "nombre" => "Electrónica",
            "descripcion" => "Categoría de tecnología"
        ];
        $this->dao->save($data);

        $id = $this->dao->getLastInsertId();
        $categoria = $this->dao->load($id);

        $this->assertEquals("Electrónica", $categoria["nombre"]);
    }

    public function testUpdateCategoria(): void
    {
        $data = ["nombre" => "Temporal", "descripcion" => ""];
        $this->dao->save($data);
        $id = $this->dao->getLastInsertId();

        $update = ["id" => $id, "nombre" => "Actualizado", "descripcion" => "Nueva descripción"];
        $this->dao->update($update);

        $categoria = $this->dao->load($id);
        $this->assertEquals("Actualizado", $categoria["nombre"]);
    }

    public function testDeleteCategoria(): void
    {
        $this->dao->save(["nombre" => "Eliminar", "descripcion" => ""]);
        $id = $this->dao->getLastInsertId();

        $this->dao->delete($id);
        $this->expectException(Exception::class);
        $this->dao->load($id);
    }
}
