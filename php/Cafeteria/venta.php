<?php
// venta.php

include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

// Inicializa la variable de sesión para almacenar la venta
session_start();

// Verifica si se ha enviado un formulario de venta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productoId = $_POST["producto"];
    $cantidad = $_POST["cantidad"];

    // Obtiene información del producto seleccionado
    $sqlProducto = "SELECT id, nombre, precio FROM productos WHERE id = $productoId";
    $resultProducto = $conn->query($sqlProducto);

    if ($resultProducto->num_rows > 0) {
        $row = $resultProducto->fetch_assoc();

        // Agrega el producto a la tabla de venta en la sesión
        $venta = isset($_SESSION['venta']) ? $_SESSION['venta'] : array();
        $venta[] = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'precio' => $row['precio'],
            'cantidad' => $cantidad
        );

        $_SESSION['venta'] = $venta;
    }
}

$sqlProductos = "SELECT id, nombre, precio FROM productos";
$resultProductos = $conn->query($sqlProductos);

// Calcula el total de la venta
$totalVenta = 0;
if (isset($_SESSION['venta'])) {
    foreach ($_SESSION['venta'] as $item) {
        $totalVenta += $item['precio'] * $item['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Venta - Sistema Cafeteria</title>
   
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/venta.css">

</head>
<body>
    <h1>Venta</h1>

    <form action="venta.php" method="post">
        <label for="producto">Seleccionar Producto:</label>
        <select name="producto" id="producto">
            <?php
            while ($row = $resultProductos->fetch_assoc()) {
                echo "<option value='" . $row["id"] . "'>" . $row["nombre"] . " - $" . $row["precio"] . "</option>";
            }
            ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="1">

        <input type="submit" value="Agregar a la Venta">
    </form>

    <!-- Mostrar la tabla de venta -->
    <?php if (isset($_SESSION['venta']) && !empty($_SESSION['venta'])) : ?>
    <h2>Detalle de Venta</h2>
    <table id="tablaVenta">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
        </tr>
        <?php foreach ($_SESSION['venta'] as $item) : ?>
            <tr>
                <td><?php echo $item['nombre']; ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>$<?php echo $item['precio']; ?></td>
                <td>$<?php echo $item['precio'] * $item['cantidad']; ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3">Total</td>
            <td>$<?php echo $totalVenta; ?></td>
        </tr>
    </table>
    <!-- Agrega un botón para reiniciar la venta -->
    <button type="button" onclick="reiniciarVenta()" class="reiniciar-button">Reiniciar Venta</button>
    <!-- Agrega un botón para finalizar la venta o cualquier otra acción que desees -->
    <button type="button" onclick="procesarVenta()" class="pagar-button">Pagar</button>
    <a href="/SistemaGaapem/php/Cafeteria/pagos.php" class="cancelar-button">Cancelar</a>
<?php endif; ?>

                <!-- Agrega un botón para pagar y enviar la información -->
    

                <script>
    function reiniciarVenta() {
        // Realiza una petición AJAX para reiniciar la variable de sesión
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Recarga la página para reflejar los cambios
                location.reload();
            }
        };
        xhr.open("GET", "reiniciarVenta.php", true);
        xhr.send();
    }
    
</script>

</body>
</html>

<?php
// Cierra la conexión a la base de datos al final del archivo
$conn->close();
?>
