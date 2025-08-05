<?php
session_start();

// Reinicia la variable de sesión 'venta'
unset($_SESSION['venta']);

// Respuesta para AJAX
echo json_encode(['success' => true]);
exit();
?>
