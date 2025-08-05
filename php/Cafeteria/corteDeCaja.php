<?php
// Incluye la conexión u otras configuraciones necesarias
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

// Inicia la sesión
session_start();

// Obtén la información de la venta desde la sesión
$venta = isset($_SESSION['venta']) ? $_SESSION['venta'] : array();

// ... (tu código existente para corteDeCaja.php) ...
var_dump($venta);
// Imprimir el ticket
echo "<h2>Detalle de Venta</h2>";
echo "<table>";
echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th></tr>";
$totalVenta = 0;

foreach ($venta as $item) {
    echo "<tr>";
    echo "<td>" . $item['nombre'] . "</td>";
    echo "<td>" . $item['cantidad'] . "</td>";
    echo "<td>$" . $item['precio'] . "</td>";
    echo "<td>$" . ($item['precio'] * $item['cantidad']) . "</td>";
    echo "</tr>";
    
    $totalVenta += $item['precio'] * $item['cantidad'];
}

echo "<tr><td colspan='3'>Total</td><td>$" . $totalVenta . "</td></tr>";
echo "</table>";

// También puedes enviar comandos de impresión específicos para la impresora GHIA
// Puedes encontrar los comandos en la documentación de la impresora o del fabricante
// ...

// Limpia la variable de sesión 'venta'
unset($_SESSION['venta']);

// Cierra la conexión a la base de datos al final del archivo
$conn->close();
?>
