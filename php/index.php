<!DOCTYPE html>
<html>
<head>
    <title>Sistema Gaapem</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/opciones.css">
</head>
<body>
    <div id="sidebar">
        <a class="menu-link" href="/SistemaGaapem/php/index.php">Inicio</a>
        <a class="menu-link" href="/SistemaGaapem/php/Alumnos/alumnos.php">Alumnos</a>
        <a class="menu-link" href="/SistemaGaapem/php/Cafeteria/pagos.php">Escolares</a>
    </div>
    <div id="main">
    <div id="header">
        <h1 id="title">Inicio</h1>
        <img id="logo" src="/SistemaGaapem/src/LogoSoft.jpeg" alt="Logo">
    </div>
    <div id="content" class="options-container">

        <a href="/SistemaGaapem/php/pagar.php" class="option">
            <h2>Pagos</h2>
            <img src="/SistemaGaapem/src/img/colegiatura.jpg" alt="Pagos">
        </a>
        <a href="/SistemaGaapem/php/factura.php" class="option">
            <h2>Corte de Caja</h2>
            <img src="/SistemaGaapem/src/img/caja.jpeg" alt="Pagos">
        </a>
        
    </div>
</div>

    <script src="/script.js"></script>
</body>
</html>
