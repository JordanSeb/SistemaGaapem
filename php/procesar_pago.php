<?php
include 'Conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    date_default_timezone_set('America/Mexico_City');
    $tipoDePago = $_POST["tipoDePago"];
    $fecha = date("Y-m-d H:i:s");
    $especialidadAlumno = $_POST["especialidad"];    
    $alumnoId = $_POST["alumno_id"];
    $detalles_producto = json_decode($_POST["detallesProductos"], true);
    $detalles = $_POST["detalles"];
    $precioPagado = $_POST["precioPagado"];
    $porPagar = $_POST["porPagar"];
    $honorarios = $_POST["honorarios"];
    $conn->begin_transaction();

    if ($alumnoId) {
        // Preparar y ejecutar consulta para obtener el nombre completo
        $stmt = $conn->prepare("SELECT CONCAT(nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombreCompleto FROM alumnos WHERE id = ?");
        $stmt->bind_param("i", $alumnoId);
        $stmt->execute();
        $stmt->bind_result($nombreCompleto);
        if ($stmt->fetch()) {
            $nombreAlumno = $nombreCompleto;
        }
        $stmt->close();
    }

    if ($especialidadAlumno === "") {
        $stmt = $conn->prepare("SELECT especialidad FROM alumnos WHERE id = ?");
        $stmt->bind_param("i", $alumnoId);
        $stmt->execute();
        $stmt->bind_result($especialidad);
        if ($stmt->fetch()) {
            $especialidadAlumno = $especialidad;
        }
        $stmt->close();
    }
    
    try {
        if ($tipoDePago === 'colegiatura') {
            $monto = $_POST["colegiaturaInput"];
            $folio = strtoupper(substr(uniqid(), -6));

            $stmtPago = $conn->prepare("INSERT INTO pagos (alumno_id, tipo_pago, total, fecha, folio) VALUES (?, ?, ?, ?, ?)");
            $stmtPago->bind_param("isdss", $alumnoId, $tipoDePago, $monto, $fecha, $folio);
            $stmtPago->execute();


        } elseif ($tipoDePago === 'cafeteria') {
            $montoTotal = 0;
            $folio = strtoupper(substr(uniqid(), -6));

            // Paso 1: Insertar en pagos
            $stmtPago = $conn->prepare("INSERT INTO pagos (tipo_pago, total, fecha, folio, precio_pagado, por_pagar, detalles) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtPago->bind_param("sdssdds", $tipoDePago, $montoTotal, $fecha, $folio, $precioPagado, $porPagar, $detalles);
            $stmtPago->execute();
            $pagoId = $conn->insert_id;

            foreach ($detalles_producto as $producto) {
                $productoId = $producto["id"];
                $cantidad = $producto["cantidad"];
                if (!is_numeric($productoId)) {
                    throw new Exception("ID de producto inválido: $productoId");
                }
                // Obtener precio y verificar inventario
                $res = $conn->query("SELECT precio, cantidad FROM productos WHERE id = $productoId FOR UPDATE");
                $prodData = $res->fetch_assoc();
                $precio = $prodData["precio"];
                $stock = $prodData["cantidad"];


                $subtotal = $cantidad * $precio;
                $montoTotal += $subtotal;

                // Insertar detalle
                $stmtDetalle = $conn->prepare("INSERT INTO detalle_pago_producto (pago_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmtDetalle->bind_param("iiidd", $pagoId, $productoId, $cantidad, $precio, $subtotal);
                $stmtDetalle->execute();
            }

            // Actualizar el total del pago
            $conn->query("UPDATE pagos SET total = $montoTotal WHERE id = $pagoId");

        } elseif ($tipoDePago === 'honorarios') {
            $stmtPago = $conn->prepare("INSERT INTO pagos (tipo_pago, total, fecha, detalles) VALUES (?, ?, ?, ?)");
            $stmtPago->bind_param("sdss", $tipoDePago, $honorarios, $fecha, $detalles);
            $stmtPago->execute();
        }

        $conn->commit();

        if ($tipoDePago === 'colegiatura') {
            header ("Location: /SistemaGaapem/php/confirmacion_pago.php?nombre=$nombreAlumno&especialidad=$especialidadAlumno&monto=$monto&folio=$folio");
            exit();
        } elseif ($tipoDePago === 'cafeteria'){
            $detalles_productos = urlencode(json_encode($detalles_producto));
            header ("Location: /SistemaGaapem/php/confirmar_cafeteria.php?detallesProductos=$detalles_productos&precioPagado=$precioPagado&porPagar=$porPagar&detalles=$detalles");
        } else {
            echo "✅ Pago procesado exitosamente";
            header("Location: /SistemaGaapem/php/index.php");
            exit();
        }
        

    } catch (Exception $e) {
        $conn->rollback();
        echo "❌ Error al procesar el pago: " . $e->getMessage();
    }
}
?>
