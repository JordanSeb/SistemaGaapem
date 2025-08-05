<!DOCTYPE html>
<html>
<head>
    <title>Sistema Registros</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/jspdf-autotable"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
    <div id="sidebar">
        <a class="menu-link" href="/SistemaGaapem/php/index.php">Inicio</a>
        <a class="menu-link" href="/SistemaGaapem/php/Alumnos/alumnos.php">Alumnos</a>
        <a class="menu-link" href="/SistemaGaapem/php/Grupos/grupos.php">Grupos</a>
        <a class="menu-link" href="/SistemaGaapem/php/Cafeteria/pagos.php">Cafeteria</a>
        <a class="menu-link" href="/SistemaGaapem/php/Registros/informes.php">Registros</a>
    </div>
    <div id="main">
        <div id="header">
            <h1 id="title">Registros</h1>
            <img id="logo" src="/SistemaGaapem/src/LogoSoft.jpeg" alt="Logo">
        </div>
        <div id="content">
            <button id="print-btn">Imprimir</button>
            <button onclick="location.reload();">Recargar Tabla</button>
            <?php
            include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';
            $queryPagos = "SELECT id, tipoDePago, detalles, total, fecha FROM pagos ORDER BY fecha DESC";
            $resultPagos = mysqli_query($conn, $queryPagos);
            echo '<table id="my-table">';
            echo '<tr><th>ID</th><th>Tipo de Pago</th><th>Detalles</th><th>Total</th><th>Fecha</th></tr>';
            while($row = mysqli_fetch_assoc($resultPagos)) {
                echo '<tr><td>'.$row['id'].'</td><td>'.$row['tipoDePago'].'</td><td>'.$row['detalles'].'</td><td>$'.$row['total'].'</td><td>'.$row['fecha'].'</td></tr>';
            }
            echo '</table>';
            ?>
        </div>
    </div>
    
    <script src="/SistemaGaapem/filtrado.js"></script>
</body>
</html>
