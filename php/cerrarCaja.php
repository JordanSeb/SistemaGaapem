<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

$fecha = date('Y-m-d'); // Obtener la fecha actual
$sql = "INSERT INTO cortemensual (fecha, entradas, salidas) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$fecha, $_POST['sumaEntradas'], $_POST['sumaSalidas']]);

$conn->close();
?>