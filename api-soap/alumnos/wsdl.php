<?php
// wsdl.php - Archivo opcional para generar WSDL

// Este archivo genera automáticamente el WSDL del servicio
// Aunque SOAP puede funcionar sin WSDL, es una buena práctica incluirlo

<?php
header('Content-Type: text/xml');
readfile('wsdl_alumnos.wsdl');

?>