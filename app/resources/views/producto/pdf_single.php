<!-- app/views/usuario/pdf_single.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Usuario</title>
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
        .user-details {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background-color: #f5f5f5; /* --light */
        }
        .user-details th, .user-details td {
            padding: 10px;
            border: 1px solid #6c757d; /* --secundario */
            text-align: left;
        }
        .user-details th {
            background-color: #1a2526; /* --dark */
            color: #ffffff; /* --white */
            width: 30%;
        }
        .user-details td {
            background-color: #f5eadb; /* --logo */
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
    <h1>Detalles del Usuario</h1>
    <table class="user-details">
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($producto['id'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Nombre</th>
            <td><?= htmlspecialchars($producto['nombre'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Codigo</th>
            <td><?= htmlspecialchars($producto['codigo'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Descripcion</th>
            <td><?= htmlspecialchars($producto['descripcion'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Cateogoria</th>
            <td><?= htmlspecialchars($producto['categoria'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Precio</th>
            <td><?= htmlspecialchars($producto['precio'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Stock</th>
            <td><?= htmlspecialchars($producto['stock'] ?? '') ?></td>
        </tr>
    </table>
    <div class="footer">
        <?php
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        ?>
        Generado el <?= date('d/m/Y H:i:s') ?> | BajoCeroWear 1.0
    </div>
</body>
</html>