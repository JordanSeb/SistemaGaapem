<?php
    include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $departamento = $_POST['departamento'];

    $sql = "INSERT INTO productos (nombre,cantidad,  precio, departamento) VALUES ('$nombre',$cantidad,  $precio, '$departamento')";

    if ($conn->query($sql) === TRUE) {
        echo "Producto agregado con éxito";
        header("Location: /SistemaGaapem/php/Cafeteria/pagos.php");
    } else {
        echo "Error agregando el producto: " . $conn->error;
    }

    $conn->close();
?>
