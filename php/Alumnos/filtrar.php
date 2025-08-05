<?php
    include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

    $nombre = $_POST['nombre'];

    $sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, direccion, fechaInicio, colegiatura, especialidad, telefono FROM alumnos WHERE nombre LIKE '%$nombre%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table id='miTabla' class='miClase' ><tr><th>ID</th><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Direccion</th><th>Telefono</th><th>Fecha de Inicio</th><th>Colegiatura</th><th>Especialidad</th><th>Actualizar</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>".$row["id"]."</td><td>".$row["nombre"]."</td><td>".$row["apellidoPaterno"]."</td><td>".$row["apellidoMaterno"]."</td><td>".$row["direccion"]."</td><td>".$row["telefono"]."</td><td>".$row["fechaInicio"]."</td><td>$".$row["colegiatura"]."</td><td>".$row["especialidad"]."</td><td><a href='actualizarAlumno.php?id=".$row["id"]."'>Actualizar</a></td><td><img src='/SistemaGaapem/src/eliminarIcon.png' alt='Eliminar' style='max-width: 20px;' data-id='".$row["id"]."' onclick='confirmDelete(\"".$row["nombre"]." ".$row["apellidoPaterno"]." ".$row["apellidoMaterno"]."\", this.getAttribute(\"data-id\"))'></td></tr>";
        }
    } else {
        echo "0 resultados";
    }

    $conn->close();
?>
