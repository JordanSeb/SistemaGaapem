<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Después de obtener los datos del formulario
$nombre = $_POST["nombre"];
$telefono = $_POST["telefono"];
$direccion = $_POST["direccion"];
$fechaInicio = $_POST["fechaInicio"];
$colegiatura = $_POST["colegiatura"];
$especialidad = $_POST["especialidad"];

// Separar apellidos en apellidoPaterno y apellidoMaterno
$nombreArray = explode(" ", $nombre);
$nombres = $nombreArray[0];
$apellidoPaterno = isset($nombreArray[1]) ? $nombreArray[1] : '';
$apellidoMaterno = isset($nombreArray[2]) ? $nombreArray[2] : '';

// Construir la consulta SQL
$sql = "INSERT INTO alumnos (nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad) 
        VALUES ('$nombres', '$apellidoPaterno', '$apellidoMaterno', '$direccion', '$telefono', '$fechaInicio', $colegiatura, '$especialidad')";


    if ($conn->query($sql) === TRUE) {
        // Redirige de nuevo a la página de "alumnos.php" después de agregar al alumno
        header("Location: alumnos.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
