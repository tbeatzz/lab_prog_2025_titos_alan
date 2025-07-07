<head>
    <?php require_once APP_DIR_TEMPLATE . 'includes/head.php'; ?>
    <title><?= htmlspecialchars($title) ?></title>

</head>

<body class="errorBody d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="display-1 text-danger">404</h1>
        <p class="lead"><?= htmlspecialchars($message) ?></p>
        <a href="home/index" class="btn btn-main">Volver al inicio</a>
    </div>
</body>

