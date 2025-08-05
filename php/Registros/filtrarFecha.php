<?php
    include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

    $fecha_actual = date("Y-m-d");

    $queryPagos = "SELECT id, tipoDePago, detalles, total, fecha FROM pagos ORDER BY fecha DESC";
            $resultPagos = mysqli_query($conn, $queryPagos);
            echo '<table id="my-table">';
            echo '<tr><th>ID</th><th>Tipo de Pago</th><th>Detalles</th><th>Total</th><th>Fecha</th></tr>';
            while($row = mysqli_fetch_assoc($resultPagos)) {
                echo '<tr><td>'.$row['id'].'</td><td>'.$row['tipoDePago'].'</td><td>'.$row['detalles'].'</td><td>'.$row['total'].'</td><td>'.$row['fecha'].'</td></tr>';
            }
            echo '</table>';

    $conn->close();
?>
