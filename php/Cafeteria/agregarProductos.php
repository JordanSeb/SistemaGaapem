<?php
session_start();

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

        // Respuesta para AJAX
        echo json_encode(['success' => true]);
        exit();
    }
}
?>
