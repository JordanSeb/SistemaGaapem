<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del formulario relacionados con los alumnos
    $nombre = $_POST["nombre"];
    $apellidoPaterno = $_POST["apellidoPaterno"];
    $apellidoMaterno = $_POST["apellidoMaterno"];
    $telefono = $_POST["telefono"];
    $fechaNacimiento = $_POST["fechaNacimiento"];
    $fechaInicio = $_POST["fechaInicio"];
    $colegiatura = $_POST["colegiatura"];
    $especialidad = $_POST["especialidad"];

    // Datos del formulario relacionados con los pagos
    $tipoDePago = $_POST["tipoDePago"];
    $detalles = $_POST["detalles"];
    $total = $_POST["total"];
    $fecha = date("Y-m-d H:i:s");

    // Iniciar la transacción
    $conn->begin_transaction();

    // Inserción en la tabla 'alumnos'
    $sqlAlumnos = "INSERT INTO alumnos (nombre, apellidoPaterno, apellidoMaterno, telefono, fechaNacimiento, fechaInicio, colegiatura, especialidad) 
                   VALUES ('$nombre', '$apellidoPaterno', '$apellidoMaterno', '$telefono','$fechaNacimiento', '$fechaInicio', $colegiatura, '$especialidad')";

    if ($conn->query($sqlAlumnos) === TRUE) {
        // Obtener el ID del último registro insertado en la tabla 'alumnos'
        $id_alumno = $conn->insert_id;

        // Inserción en la tabla 'pagos' asociada al ID del alumno en la tabla 'alumnos'
        $sqlPagos = "INSERT INTO pagos ( tipoDePago, detalles, total, fecha) 
                     VALUES ( '$tipoDePago', '$detalles', '$total', '$fecha')";

        if ($conn->query($sqlPagos) === TRUE) {
            // Confirmar la transacción si todo fue exitoso
            $conn->commit();

            // Redirige de nuevo a la página de "alumnos.php" después de agregar al alumno
            header("Location: alumnos.php");
            exit();
        } else {
            echo "Error en la inserción de pagos: " . $sqlPagos . "<br>" . $conn->error;
        }
    } else {
        echo "Error en la inserción de alumnos: " . $sqlAlumnos . "<br>" . $conn->error;
    }

    // Deshacer la transacción en caso de algún problema
    $conn->rollback();
    $conn->close();
}
?>
