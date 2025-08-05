<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Pago</title>

    <!-- Agrega aquí tus estilos o enlaces a archivos de estilo -->
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/folio.css">

</head>
<body>
    <img src="/SistemaGaapem/src/img/gaapemlogo22.png" alt="Logo">
    <h1>Confirmación de Pago</h1>
    <p>MORELOS 161 INT 7-8-9, COL CENTRO</p>
    <p>ZAMORA, MICHOACAN.</p>
    <p>(351) 688-3376</p>  
    <?php
    // Mostrar información del pago
    date_default_timezone_set('America/Mexico_City');

    $folio = $_GET['folio'];
    
    $especialidad = $_GET['especialidad'];
    $nombreAlumno = $_GET['nombre'];
    $colegiatura = $_GET['monto'];
    $fechaActual = strftime('%d/%m/%Y');
    
    echo "<p>Folio: $folio</p>";
    echo "<p>Alumno: $nombreAlumno</p>";
    echo "<p>Alumno: $especialidad</p>";
    echo "<p>Tipo de Pago: Colegiatura</p>";
    echo "<p>Monto: $$colegiatura</p>";
    
    echo "<p>Fecha de Pago: $fechaActual</p>";
    
    ?>
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
