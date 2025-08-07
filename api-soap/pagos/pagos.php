<?php
// habilita errores para depuración (quítalo en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../php/Conexion.php'; // ajusta ruta
$wsdl = __DIR__ . '/pagos.wsdl'; // asume que está junto

$server = new SoapServer($wsdl);

// Función: obtenerPago
$server->addFunction(function($params) use ($conn) {
    $folio = $params['folio'] ?? '';
    if (!$folio) {
        return ['pago' => json_encode(['error' => 'Folio requerido'])];
    }

    $stmt = $conn->prepare("SELECT * FROM pagos WHERE folio = ?");
    $stmt->bind_param("s", $folio);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return ['pago' => json_encode($row)];
    } else {
        return ['pago' => json_encode(['error' => 'Pago no encontrado'])];
    }
}, 'obtenerPago');

// Función: registrarColegiatura
$server->addFunction(function($params) use ($conn) {
    $alumno_id = $params['alumno_id'] ?? null;
    $monto = $params['monto'] ?? null;
    if (!$alumno_id || !$monto) {
        return ['folio' => '', 'status' => 'Faltan parámetros'];
    }

    $folio = strtoupper(substr(uniqid(), -6));
    $tipo_pago = 'colegiatura';
    $fecha = date("Y-m-d H:i:s");
    $total = $monto;
    $precio_pagado = $monto;
    $por_pagar = 0.00;

    $stmt = $conn->prepare("INSERT INTO pagos (alumno_id, tipo_pago, total, fecha, folio, precio_pagado, por_pagar) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsddd", $alumno_id, $tipo_pago, $total, $fecha, $folio, $precio_pagado, $por_pagar);
    if ($stmt->execute()) {
        return ['folio' => $folio, 'status' => 'OK'];
    } else {
        return ['folio' => '', 'status' => 'Error: ' . $stmt->error];
    }
}, 'registrarColegiatura');

// Función: registrarCafeteria
$server->addFunction(function($params) use ($conn) {
    $detallesProductos = $params['detallesProductos'] ?? '';
    $precio_pagado = $params['precio_pagado'] ?? 0;
    $por_pagar = $params['por_pagar'] ?? 0;
    $detalles = $params['detalles'] ?? '';

    $folio = strtoupper(substr(uniqid(), -6));
    $tipo_pago = 'cafeteria';
    $fecha = date("Y-m-d H:i:s");
    $montoTotal = 0;

    // iniciar transacción
    $conn->begin_transaction();

    $stmtPago = $conn->prepare("INSERT INTO pagos (tipo_pago, total, fecha, folio, precio_pagado, por_pagar, detalles) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtPago->bind_param("sdssdds", $tipo_pago, $montoTotal, $fecha, $folio, $precio_pagado, $por_pagar, $detalles);
    if (!$stmtPago->execute()) {
        $conn->rollback();
        return ['folio' => '', 'total' => 0, 'status' => 'Error al insertar pago: ' . $stmtPago->error];
    }
    $pagoId = $conn->insert_id;

    $productos = json_decode($detallesProductos, true);
    foreach ($productos as $producto) {
        $productoId = $producto['id'];
        $cantidad = $producto['cantidad'];

        if (!is_numeric($productoId)) {
            $conn->rollback();
            return ['folio' => '', 'total' => 0, 'status' => "ID inválido: $productoId"];
        }

        $res = $conn->query("SELECT precio, cantidad FROM productos WHERE id = $productoId FOR UPDATE");
        $prodData = $res->fetch_assoc();
        $precioUnitario = $prodData['precio'];

        $subtotal = $cantidad * $precioUnitario;
        $montoTotal += $subtotal;

        $stmtDetalle = $conn->prepare("INSERT INTO detalle_pago_producto (pago_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmtDetalle->bind_param("iiidd", $pagoId, $productoId, $cantidad, $precioUnitario, $subtotal);
        if (!$stmtDetalle->execute()) {
            $conn->rollback();
            return ['folio' => '', 'total' => 0, 'status' => 'Error detalle: ' . $stmtDetalle->error];
        }
    }

    // actualizar total
    $conn->query("UPDATE pagos SET total = $montoTotal WHERE id = $pagoId");
    $conn->commit();

    return ['folio' => $folio, 'total' => $montoTotal, 'status' => 'OK'];
}, 'registrarCafeteria');

$server->handle();
