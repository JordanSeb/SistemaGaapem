<?php
include 'C:/xampp/htdocs/SistemaGaapem/api-rest/conexion.php';

$sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad FROM alumnos ORDER BY id asc";
$resultado = mysqli_query($conn, $sql);

if ($resultado === false) {
    echo json_encode([
        "error" => "Error al ejecutar la consulta",
        "detalle" => sqlsrv_errors()
    ]);
    exit;
}

$alumnos = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $alumnos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($alumnos);
?>