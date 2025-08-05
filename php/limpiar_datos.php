<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

// Primero eliminar detalle_pago_producto
$queryDetalle = "DELETE FROM detalle_pago_producto";
if (mysqli_query($conn, $queryDetalle)) {
    echo "Datos de la tabla 'detalle_pago_producto' eliminados exitosamente.<br>";
} else {
    echo "Error al limpiar datos de la tabla 'detalle_pago_producto': " . mysqli_error($conn) . "<br>";
}

// Luego eliminar pagos
$queryPagos = "DELETE FROM pagos";
if (mysqli_query($conn, $queryPagos)) {
    echo "Datos de la tabla 'pagos' eliminados exitosamente.<br>";
    header("Location: /SistemaGaapem/php/index.php");
} else {
    echo "Error al limpiar datos de la tabla 'pagos': " . mysqli_error($conn) . "<br>";
}

$conn->close();
?>
