<?php
// Recuperar los valores de la URL
$detalles = $_GET['detalles'];
$precioPagado = $_GET['precioPagado'];
$porPagar = $_GET['porPagar'];


// Decodificar los detalles JSON
$detallesProductos = json_decode(urldecode($_GET['detallesProductos']), true);
// Verificar si hay productos
?>

<!DOCTYPE html>
<html>
<head>
    <title>Factura</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/folio.css">
</head>
<body>
    <img src="/SistemaGaapem/src/img/gaapemlogo22.png" alt="Logo">
    <p>MORELOS 161 INT 7-8-9, COL CENTRO</p>
    <p>ZAMORA, MICHOACAN.</p>
    <p>(351) 688-3376</p>  
    <?php
    // Configura la zona horaria de México
    date_default_timezone_set('America/Mexico_City');
    ?>
    <p>Fecha: <?php echo date('d/m/Y'); ?></p> <!-- Agrega esta línea para mostrar la fecha actual -->

    <?php
    if (!empty($detallesProductos)) {
        echo "<h2>Detalles de Productos:</h2>";
        echo "<ul>";
    
        // Iterar a través de los detalles de productos
        foreach ($detallesProductos as $producto) {
            $nombreProducto = $producto['nombre'];
            $cantidad = $producto['cantidad'];
            $precioUnitario = $producto['precio'];
            $totalProducto = $cantidad * $precioUnitario;
    
            echo "<li>$nombreProducto x $cantidad: $ $totalProducto</li>";
        }
    
        echo "</ul>";
    } else {
        echo "<p>No hay productos detallados.</p>";
    }
    ?>
    <p>Detalles: <?php echo $detalles; ?></p>
    <p>Precio Pagado:$<?php echo $precioPagado; ?></p>
    <p>Por Pagar:$<?php echo $porPagar; ?></p>
    
 

    <button id="botonImprimir" onclick="imprimir()">Imprimir</button>
    <button id="botonSalir" onclick="regresar()">Salir</button>
<script>
    function imprimir() {
        window.print();
    }
    function regresar() {
            // Cambia la ruta "/ruta/especifica" por la ruta a la que deseas regresar
            window.location.href = "/SistemaGaapem/php/pagar.php";
        }
</script>
</body>
</html>
