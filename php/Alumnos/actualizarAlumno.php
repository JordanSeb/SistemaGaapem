<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

// Obtener el ID del alumno de la URL
$alumnoId = $_GET['id'];

// Obtener los datos actuales del alumno
$sql = "SELECT * FROM alumnos WHERE id = $alumnoId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $alumno = $result->fetch_assoc();
} else {
    echo "Alumno no encontrado";
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Alumno</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/agregar.css">

    <!-- Agrega tus estilos y scripts necesarios -->
</head>
<body>
    <h2>Actualizar Datos del Alumno</h2>
    <form action="actualizar.php" method="post">
        <!-- Agrega los campos del formulario con los valores actuales del alumno -->
        <input type="hidden" name="alumnoId" value="<?php echo $alumno['id']; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $alumno['nombre']; ?>">
        <label for="apellidoPaterno">Apellido Paterno:</label>
        <input type="text" id="apellidoPaterno" name="apellidoPaterno" value="<?php echo $alumno['apellidoPaterno']; ?>">
        <label for="apellidoMaterno">Apellido Materno:</label>
        <input type="text" id="apellidoMaterno" name="apellidoMaterno" value="<?php echo $alumno['apellidoMaterno']; ?>">
        <label for="direccion">Direccion:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $alumno['direccion']; ?>">
        <label for="telefono">Telefono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo $alumno['telefono']; ?>">
        <label for="fechaInicio">Fecha de Inicio:</label>
        <input type="date" id="fechaInicio" name="fechaInicio" value="<?php echo $alumno['fechaInicio']; ?>">
        <label for="colegiatura">Colegiatura:</label>
        <input type="number" id="colegiatura" name="colegiatura" value="<?php echo $alumno['colegiatura']; ?>">
        <label for="especialidad">Especialidad:</label>
        <input type="text" id="especialidad" name="especialidad" value="<?php echo $alumno['especialidad']; ?>">
        <!-- Agrega otros campos del formulario con sus valores actuales -->
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
