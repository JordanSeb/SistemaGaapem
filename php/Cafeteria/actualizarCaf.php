<?php
    include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $departamento = $_POST['departamento']; // Aquí es donde faltaba el punto y coma

    $sql = "UPDATE productos SET nombre='$nombre',cantidad=$cantidad, precio=$precio, departamento='$departamento' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
        header("Location: /SistemaGaapem/php/Cafeteria/pagos.php");
    } else {
        echo "Error actualizando el registro: " . $conn->error;
    }

    $conn->close();
?>
