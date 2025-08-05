<?php
session_start();

// Obtén la información de la venta desde la solicitud POST
$venta = isset($_POST['venta']) ? json_decode($_POST['venta'], true) : array();

// Aquí puedes procesar la información de la venta según tus necesidades
// ...

// Reinicia la variable de sesión 'venta'
unset($_SESSION['venta']);

// Respuesta para AJAX
echo json_encode(['success' => true]);
exit();
?>
