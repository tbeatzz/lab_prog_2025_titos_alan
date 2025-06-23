<?php

require_once '../../app/config/AppConfig.php';
require_once '../../app/config/DBConfig.php';
require_once '../../app/vendor/autoload.php';

use app\core\models\dto\ProductoDto;
use app\core\services\ProductoService;

try {
    // 1. DTO de prueba
    $data = [
        "id" => 0,
        "nombre" => "Remera Oversize Blanca",
        "codigo" => "REM-BL-001",
        "descripcion" => "Remera oversize de algodón, blanca, edición limitada.",
        "categoriaId" => 1,
        "precio" => 10999.99,
        "stock" => 50
    ];

    $dto = new ProductoDto($data);
    $service = new ProductoService();

    // 2. Guardar producto
    $service->save($dto);
    echo "<p style='color:green;'>✅ Producto guardado correctamente.</p>";

    // 3. Listar productos con filtro por código
    $productos = $service->list(["codigo" => "REM-BL-001"]);
    echo "<pre>📦 Productos encontrados:\n" . print_r($productos, true) . "</pre>";

    // 4. Cargar el producto individualmente (por ID)
    $ultimo = end($productos);
    $cargado = $service->load($ultimo["id"]);
    echo "<p>🔍 Producto cargado: <strong>{$cargado->getNombre()}</strong></p>";

    // 5. Actualizar
    $cargado->setStock(80);
    $cargado->setPrecio(11999.99);
    $service->update($cargado);
    echo "<p style='color:orange;'>🛠 Producto actualizado correctamente.</p>";

    // 6. Eliminar
    $service->delete($cargado);
    echo "<p style='color:red;'>❌ Producto eliminado correctamente.</p>";

} catch (\PDOException $ex) {
    echo "<p style='color:red;'>Error DB: {$ex->getMessage()}</p>";
} catch (\Exception $ex) {
    echo "<p style='color:red;'>Error Sistema: {$ex->getMessage()}</p>";
}
