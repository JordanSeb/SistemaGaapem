<?php
//Version
// Incluir tu archivo de conexión
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';
date_default_timezone_set('America/Mexico_City'); // Establecer la zona horaria de México

$fechaActual = date("Y-m-d");

$sqlCafeteria = "
SELECT SUM(dp.subtotal) AS total_cafeteria
FROM pagos pg
JOIN detalle_pago_producto dp ON pg.id = dp.pago_id
JOIN productos p ON dp.producto_id = p.id
WHERE pg.tipo_pago = 'cafeteria' AND p.departamento <> 'ESCOLARES' AND DATE(pg.fecha) = '$fechaActual'";
$resultCafeteria = $conn->query($sqlCafeteria);

// Obtener las colegiaturas
$sqlColegiatura = "
SELECT pg.*, CONCAT(a.nombre, ' ', a.apellidoPaterno, ' ', a.apellidoMaterno) AS nombre_alumno
FROM pagos pg
JOIN alumnos a ON pg.alumno_id = a.id
WHERE pg.tipo_pago = 'colegiatura' AND DATE(pg.fecha) = '$fechaActual'";
$resultColegiaturas = $conn->query($sqlColegiatura);

// Obtener las salidas de dinero
$sqlSalidas = "SELECT tipo_Pago, detalles, total, fecha FROM pagos WHERE tipo_Pago IN ('honorarios', 'otros') AND DATE(fecha) = '$fechaActual'";
$resultSalidas = $conn->query($sqlSalidas);

$sqlKITS = "
SELECT p.nombre AS producto_nombre, pg.detalles, dp.cantidad, dp.precio_unitario, dp.subtotal,
pg.precio_pagado, pg.por_pagar
FROM pagos pg
JOIN detalle_pago_producto dp ON pg.id = dp.pago_id
JOIN productos p ON dp.producto_id = p.id
WHERE pg.tipo_pago = 'cafeteria' AND p.departamento = 'ESCOLARES' AND DATE(pg.fecha) = '$fechaActual'";
$resultKITS = $conn->query($sqlKITS);



$resultKITS = $conn->query($sqlKITS);


$conn->close();

$datosKITS = array();
$datosSalidas = array();
$sumaKITS = 0;
$sumaPorPagar = 0;
// Inicializar totales
$sumaEntradas = 0;
$sumaSalidas = 0;
$datosColegiatura = array();
$datosEscolares = array();
// Calcular total de entradas
$row = $resultCafeteria->fetch_assoc();
$totalCafeteria = $row["total_cafeteria"];

while ($row = $resultColegiaturas->fetch_assoc()) {
    $sumaEntradas += $row["total"];
    $datosColegiatura[] = $row;
}
// Calcular total de salidas
while ($row = $resultSalidas->fetch_assoc()) {
    $sumaSalidas += $row["total"];
    $datosSalidas[] = $row;
}

while ($row = $resultKITS->fetch_assoc()) {
    // Asignar el valor a $sumaEntradas primero
    $sumaEntradas += $row["precio_pagado"];
    $row["total"] = $sumaEntradas;  // Asignar el valor actualizado a la fila

    // Luego agregar la fila al array $datosKITS
    $datosKITS[] = $row;

    $sumaKITS += $row["precio_pagado"];
    $sumaPorPagar += $row["por_pagar"];
}
// Calcular total en caja
$totalEnCaja = $sumaEntradas - $sumaSalidas;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/factura.css">
</head>
<body>
    <div id="sidebar">
        
        <!-- Agrega tus enlaces de menú si es necesario -->
    </div>
    <div id="main">
        <div id="header">
           
        </div>
        <div id="content">
       

            <h2>Colegiaturas</h2>
            <table>
                <tr>
                    <th>Detalles</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
                <?php
                $sumaColegiaturas = 0; // Inicializar la suma
                foreach ($datosColegiatura as $row) {
                    echo "<tr>";
                    echo "<td>".$row["nombre_alumno"]."</td>";
                    echo "<td>$".$row["total"]."</td>";
                    echo "<td>".$row["fecha"]."</td>";
                    echo "</tr>";
                    $sumaColegiaturas += $row["total"];
                }
                // Agregar la fila de suma
                echo "<tr>";
                echo "<td colspan='2'>Total:</td>";
                echo "<td>$".$sumaColegiaturas."</td>";
                echo "</tr>";
                ?>
            </table>

            <h2>Salidas de Dinero</h2>
            <table>
                <tr> 
                    <th>Detalles</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
                <?php
                $sumaSalidas = 0; // Inicializar la suma
                foreach ($datosSalidas as $row) {
                    echo "<tr>";
                    echo "<td>".$row["detalles"]."</td>";
                    echo "<td>$".$row["total"]."</td>";
                    echo "<td>".$row["fecha"]."</td>";
                    echo "</tr>";

                    $sumaSalidas += $row["total"]; // Sumar al total
                }
                // Agregar la fila de suma
                echo "<tr>";
                echo "<td colspan='2'>Total:</td>";
                echo "<td>$".$sumaSalidas."</td>";
                echo "</tr>";
                ?>
            </table>
            <h2>KITS, Manuales, Uniformes y Recargos</h2>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Detalles</th>
                    <th>Total Pagado</th>
                    <th>Por Pagar</th>
                </tr>
                <?php
                // Mostrar resultados después de almacenar los datos
                foreach ($datosKITS as $row) {
                    echo "<tr>";
                    echo "<td>".$row["producto_nombre"]."</td>";
                    echo "<td>".$row["detalles"]."</td>";
                    echo "<td>$".$row["precio_pagado"]."</td>";
                    echo "<td>$".$row["por_pagar"]."</td>";
                    echo "</tr>";
                }

                // Agregar la fila de suma
                echo "<tr>";
                echo "<td colspan='2'>Total:</td>";
                echo "<td>$".$sumaKITS."</td>";
                echo "<td>$".$sumaPorPagar."</td>";
                echo "</tr>";
                ?>
            </table>

            <!-- Agregar el botón de imprimir -->
            <h2>Total Entradas</h2>
            <p>$<?= $sumaEntradas ?></p>

            <h2>Total Salidas</h2>
            <p>$<?= $sumaSalidas ?></p>

            <h2>Total En Caja</h2>
            <p>$<?= $totalEnCaja ?></p>

            
        </div>
        <div class="form-container">

        <form id="formCerrarCaja" method="post" action="/SistemaGaapem/php/limpiar_datos.php">
    <button type="button" onclick="confirmarCerrarCaja()">Cerrar Turno De Caja</button>
</form>
<!-- Segundo formulario -->
<form method="post" action="/SistemaGaapem/php/enviar_correo.php" enctype="multipart/form-data">
    <input type="file" name="archivo_adjunto" />
    <button type="submit" name="enviar_correo">Enviar por correo</button>
</form>

</div>
<div id="loading-overlay" style="display: none;">
  <div class="spinner"></div>
</div>
<!-- Botón fuera del contenedor principal -->
<button onclick="window.print()">Imprimir Corte Actual</button>
<button id="ver-corte-mensual">Ver Corte Mensual</button>

<a href="/SistemaGaapem/php/index.php">Volver al Inicio</a>
    </div>

    <script>
function confirmarCerrarCaja() {
    // Mostrar un cuadro de confirmación
    var confirmacion = confirm("¿Estás seguro de que quieres cerrar el turno de caja?");

    // Verificar la respuesta del usuario
    if (confirmacion) {
        // Si el usuario hace clic en "Aceptar", enviar el formulario
        document.getElementById("formCerrarCaja").submit();
    } else {
        // Si el usuario hace clic en "Cancelar", no hacer nada
        // Puedes agregar un mensaje o realizar otras acciones si lo deseas
    }
}

document.getElementById("formCerrarCaja").addEventListener("submit", function(event) {
    // Evitar el comportamiento predeterminado del formulario
    event.preventDefault();

    // Enviar una solicitud AJAX al servidor
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/SistemaGaapem/php/cerrarCaja.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Si la solicitud se completó correctamente, mostrar un mensaje de éxito
            alert("Turno de caja cerrado exitosamente");
        }
    };
    // Enviar los valores de sumaEntradas y sumaSalidas al servidor
    xhr.send("sumaEntradas=" + <?php echo $sumaEntradas; ?> + "&sumaSalidas=" + <?php echo $sumaSalidas; ?>);
});

document.getElementById('ver-corte-mensual').addEventListener('click', function() {
  // Mostrar la pantalla de carga
    document.getElementById('loading-overlay').style.display = 'flex';

  // Redirigir a la página del corte mensual después de 2 segundos
    setTimeout(function() {
    window.location.href = '/corte-mensual';
    }, 2000);
});
</script>
</body>
</html>
