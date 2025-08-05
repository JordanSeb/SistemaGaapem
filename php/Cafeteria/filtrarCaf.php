<?php
        include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

        $producto = $_POST['producto'];

        $sql = "SELECT id, nombre, precio, cantidad, departamento FROM productos WHERE nombre LIKE '%$producto%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table id='miTabla' class='miClase' ><tr><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Departamento</th><th>Acciones</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["nombre"]."</td><td>$".$row["precio"]."</td><td>".$row["cantidad"]."</td><td>".$row["departamento"]."</td><td><img src='/SistemaGaapem/src/eliminarIcon.png' alt='Eliminar' style='max-width: 20px;' data-id='".$row["id"]."' onclick='confirmDelete(\"".$row["nombre"]."\", this.getAttribute(\"data-id\"))'><img src='/SistemaGaapem/src/img/actualizarIcon.png' alt='Actualizar' style='max-width: 20px;' data-id='".$row["id"]."' onclick='confirmUpdate(\"".$row["id"]."\", \"".$row["nombre"]."\", \"".$row["cantidad"]."\", \"".$row["precio"]."\", \"".$row["departamento"]."\"),document.documentElement.scrollIntoView()'></td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 resultados";
                }
                $conn->close();
 ?>