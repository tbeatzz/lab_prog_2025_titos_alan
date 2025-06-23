<?php

require_once '../../app/config/AppConfig.php';
require_once '../../app/config/DBConfig.php';
require_once '../../app/vendor/autoload.php';

use app\core\models\dto\UserDto;
use app\core\services\UserService;

try {
    // Crear DTO de prueba
    $data = [
        "id" => 0,
        "apellido" => "Titos",
        "nombres" => "Alan",
        "cuenta" => "alantitos",
        "perfil" => "ADMIN",
        "clave" => password_hash("1234", PASSWORD_DEFAULT),
        "correo" => "alan@bajocerowear.com",
        "estado" => "1",
        "fechaAlta" => date("Y-m-d"),
        "resetPass" => false
    ];
    
    $dto = new UserDto($data);
    $service = new UserService();

    // PRUEBA DE GUARDADO
    $service->save($dto);
    echo "<p style='color: green;'>✅ Usuario agregado correctamente.</p>";

    // PRUEBA DE LISTADO
    $usuarios = $service->list(["cuenta" => "alantitos"]);
    echo "<pre>" . print_r($usuarios, true) . "</pre>";

    // PRUEBA DE CARGA INDIVIDUAL
    $ultimoId = end($usuarios)["id"];
    $usuarioCargado = $service->load($ultimoId);
    echo "<p>👤 Usuario cargado: {$usuarioCargado->getNombres()} {$usuarioCargado->getApellido()}</p>";

    // PRUEBA DE ACTUALIZACIÓN
    $usuarioCargado->setApellido("Titos (Editado)");
    $service->update($usuarioCargado);
    echo "<p style='color: orange;'>🛠 Usuario actualizado correctamente.</p>";

    // PRUEBA DE ELIMINACIÓN
    $service->delete($usuarioCargado);
    echo "<p style='color: red;'>❌ Usuario eliminado correctamente.</p>";

} catch (\PDOException $ex) {
    echo "<p style='color:red;'>Error DB: {$ex->getMessage()}</p>";
} catch (\Exception $ex) {
    echo "<p style='color:red;'>Error Sistema: {$ex->getMessage()}</p>";
}
