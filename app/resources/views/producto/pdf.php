
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #212121; /* --black */
            margin: 20px;
        }
        h1 {
            color: #e63946; /* --primario */
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #6c757d; /* --secundario */
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            background-color: #f5f5f5; /* --light */
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #6c757d; /* --secundario */
        }
        th {
            background-color: #1a2526; /* --dark */
            color: #ffffff; /* --white */
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f5eadb; /* --logo */
        }
        tr:hover {
            background-color: #ffffff; /* --white */
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #6c757d; /* --secundario */
        }
    </style>
</head>
<body>
    <h1>Listado de productos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Cateogoria</th>
                <th>Precio</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($producto['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($producto['codigo'] ?? '') ?></td>
                    <td><?= htmlspecialchars($producto['descripcion'] ?? '') ?></td>
                    <td><?= htmlspecialchars($producto['categoria'] ?? '') ?></td>
                    <td><?= htmlspecialchars($producto['precio'] ?? '') ?></td>
                    <td><?= htmlspecialchars($producto['stock'] ?? '') ?></td>
           
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="footer">
        <?php
        // Configurar la zona horaria de Argentina
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        ?>
        Generado el <?= date('d/m/Y H:i:s') ?> | BajoCeroWear 1.0
    </div>
</body>
</html>