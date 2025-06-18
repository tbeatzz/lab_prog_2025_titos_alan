<?php

use PHPUnit\Framework\TestCase;
use app\core\models\dao\UserDao;

final class UserDaoTest extends TestCase
{
    private UserDao $dao;

    protected function setUp(): void
    {
        $pdo = new PDO("mysql:host=localhost;dbname=test_db", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dao = new UserDao($pdo);
    }

    public function testSaveAndLoadUser(): void
    {
        $data = [
            "apellido" => "Pérez",
            "nombres" => "Juan",
            "cuenta" => "jperez",
            "perfil" => "Operador",
            "clave" => password_hash("1234", PASSWORD_BCRYPT),
            "correo" => "jperez@mail.com",
            "estado" => 1,
            "fechaAlta" => date("Y-m-d"),
            "resetPass" => 0
        ];
        $this->dao->save($data);

        $id = $this->dao->getLastInsertId();
        $user = $this->dao->load($id);

        $this->assertEquals("jperez", $user["cuenta"]);
    }

    public function testEnableDisableUser(): void
    {
        $this->dao->save([
            "apellido" => "Test",
            "nombres" => "Usuario",
            "cuenta" => "tuser",
            "perfil" => "Administrador",
            "clave" => "clave",
            "correo" => "test@mail.com",
            "estado" => 0,
            "fechaAlta" => date("Y-m-d"),
            "resetPass" => 0
        ]);
        $id = $this->dao->getLastInsertId();

        $this->dao->enable($id);
        $user = $this->dao->load($id);
        $this->assertEquals(1, $user["estado"]);

        $this->dao->disable($id);
        $user = $this->dao->load($id);
        $this->assertEquals(0, $user["estado"]);
    }

    public function testResetUser(): void
    {
        $this->dao->save([
            "apellido" => "Reset",
            "nombres" => "Clave",
            "cuenta" => "resetuser",
            "perfil" => "Operador",
            "clave" => "123",
            "correo" => "reset@mail.com",
            "estado" => 1,
            "fechaAlta" => date("Y-m-d"),
            "resetPass" => 0
        ]);
        $id = $this->dao->getLastInsertId();

        $this->dao->reset($id);
        $user = $this->dao->load($id);
        $this->assertEquals(1, $user["resetPass"]);
    }
}
