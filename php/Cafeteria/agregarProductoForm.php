<!DOCTYPE html>
<html>
<head>
    <title>Agregar Producto</title>
    <!-- Incluye los estilos CSS necesarios -->
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/agregar.css">

    <!-- Otros estilos personalizados si los necesitas -->
</head>
<body>
    <div id="main">
        <div id="header">
            <h1 id="title">Agregar Producto</h1>
           
        </div>
        <div id="formularioAgregar" style="display:none;">
    <form action="agregar.php" method="post">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre"><br>
        <label for="cantidad">Cantidad:</label><br>
        <input type="number" id="cantidad" name="cantidad"><br>
        <label for="precio">Precio:</label><br>
        <input type="number" id="precio" name="precio"><br>
        <label for="departamento">Departamento:</label><br>
        <input type="text" id="departamento" name="departamento"><br>
        <input type="submit" value="Agregar">
    </form>
</div>
    </div>
</body>
</html>
