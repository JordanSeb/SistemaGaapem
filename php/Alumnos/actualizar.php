<?php
    include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

    $id = $_POST['alumnoId'];
    $nombre = $_POST['nombre'];
    $apellidoPaterno = $_POST['apellidoPaterno'];
    $apellidoMaterno = $_POST['apellidoMaterno'];
   
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fechaInicio = $_POST['fechaInicio'];
    $colegiatura = $_POST['colegiatura'];
    $especialidad = $_POST['especialidad'];

    $sql = "UPDATE alumnos SET nombre='$nombre', apellidoPaterno='$apellidoPaterno', apellidoMaterno='$apellidoMaterno', telefono='$telefono', direccion='$direccion', fechaInicio='$fechaInicio', colegiatura=$colegiatura, especialidad='$especialidad' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro actualizado con éxito";
        header("Location: /SistemaGaapem/php/Alumnos/alumnos.php"); 
    } else {
        echo "Error actualizando el registro: " . $conn->error;
    }

    $conn->close();
?>
