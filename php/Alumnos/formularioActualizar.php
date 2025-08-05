<!DOCTYPE html>
<html>
<head>
    <title>Actualizar alumno</title>
    <!-- Incluye los estilos CSS necesarios -->
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/agregar.css">

    <!-- Otros estilos personalizados si los necesitas -->
</head>
<body>
    <div id="main">
        <div id="header">
            <h1 id="title">Actualizar alumno</h1>
           
        </div>
        <div id="content">
        <form action="actualizar.php" method="post">
            <input type="hidden" name="id" id="id">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre"><br>
            <label for="apellidoPaterno">Apellido Paterno:</label><br>
            <input type="text" id="apellidoPaterno" name="apellidoPaterno"><br>
            <label for="apellidoMaterno">Apellido Materno:</label><br>
            <input type="text" id="apellidoMaterno" name="apellidoMaterno"><br>
            <label for="fechaNacimiento">Fecha de Nacimiento:</label><br>
            <input type="date" id="fechaNacimiento" name="fechaNacimiento"><br>
            <label for="fechaInicio">Fecha de Inicio:</label><br>
            <input type="date" id="fechaInicio" name="fechaInicio"><br>
            <label for="colegiatura">Colegiatura:</label><br>
            <input type="number" id="colegiatura" name="colegiatura"><br>
            <label for="especialidad">Especialidad:</label><br>
            <input type="text" id="especialidad" name="especialidad"><br>
            <input type="submit" value="Actualizar">
        </form>

        </div>
    </div>
</body>
</html>
