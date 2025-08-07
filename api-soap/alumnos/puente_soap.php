<?php
// puente_soap.php - Archivo puente para consumir SOAP desde JavaScript

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar peticiones OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Configuración del cliente SOAP
    $client = new SoapClient(null, [
        'location' => 'http://localhost/SistemaGaapem/api-soap/alumnos/servidor_soap_alumnos.php',
        'uri' => 'http://localhost/SistemaGaapem/api-soap/alumnos',
        'soap_version' => SOAP_1_2,
        'trace' => true,
        'exceptions' => true
    ]);

    // Obtener la acción solicitada
    $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

    switch ($accion) {
        case 'obtenerAlumnos':
            $resultado = $client->obtenerAlumnos();
            echo json_encode($resultado);
            break;

        case 'obtenerAlumno':
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $resultado = $client->obtenerAlumno($id);
                echo json_encode($resultado);
            } else {
                echo json_encode("ID de alumno inválido");
            }
            break;

        case 'crearAlumno':
            $nombre = $_POST['nombre'] ?? '';
            $apellidoPaterno = $_POST['apellidoPaterno'] ?? '';
            $apellidoMaterno = $_POST['apellidoMaterno'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fechaInicio = $_POST['fechaInicio'] ?? '';
            $colegiatura = floatval($_POST['colegiatura'] ?? 0);
            $especialidad = $_POST['especialidad'] ?? '';

            // Validación básica
            if (empty($nombre) || empty($apellidoPaterno) || empty($apellidoMaterno)) {
                echo json_encode("Faltan datos obligatorios: nombre y apellidos");
                break;
            }

            $resultado = $client->crearAlumno(
                $nombre, 
                $apellidoPaterno, 
                $apellidoMaterno, 
                $direccion, 
                $telefono, 
                $fechaInicio, 
                $colegiatura, 
                $especialidad
            );
            echo json_encode($resultado);
            break;

        case 'actualizarAlumno':
            $id = intval($_POST['id'] ?? 0);
            $nombre = $_POST['nombre'] ?? '';
            $apellidoPaterno = $_POST['apellidoPaterno'] ?? '';
            $apellidoMaterno = $_POST['apellidoMaterno'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fechaInicio = $_POST['fechaInicio'] ?? '';
            $colegiatura = floatval($_POST['colegiatura'] ?? 0);
            $especialidad = $_POST['especialidad'] ?? '';

            if ($id > 0 && !empty($nombre) && !empty($apellidoPaterno)) {
                $resultado = $client->actualizarAlumno(
                    $id, 
                    $nombre, 
                    $apellidoPaterno, 
                    $apellidoMaterno, 
                    $direccion, 
                    $telefono, 
                    $fechaInicio, 
                    $colegiatura, 
                    $especialidad
                );
                echo json_encode($resultado);
            } else {
                echo json_encode("Datos inválidos para actualización");
            }
            break;

        case 'eliminarAlumno':
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $resultado = $client->eliminarAlumno($id);
                echo json_encode($resultado);
            } else {
                echo json_encode("ID de alumno inválido");
            }
            break;

        case 'obtenerAlumnosPorEspecialidad':
            $especialidad = $_POST['especialidad'] ?? '';
            if (!empty($especialidad)) {
                $resultado = $client->obtenerAlumnosPorEspecialidad($especialidad);
                echo json_encode($resultado);
            } else {
                echo json_encode("Especialidad no especificada");
            }
            break;

        case 'ping':
            // Prueba de conectividad
            echo json_encode([
                'status' => 'ok',
                'mensaje' => 'Puente SOAP funcionando correctamente',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            break;

        case 'info':
            // Información del servicio
            echo json_encode([
                'servicio' => 'Puente SOAP-AJAX para Sistema GAAPEM',
                'version' => '1.0',
                'funciones_disponibles' => [
                    'obtenerAlumnos',
                    'obtenerAlumno',
                    'crearAlumno',
                    'actualizarAlumno',
                    'eliminarAlumno',
                    'obtenerAlumnosPorEspecialidad',
                    'ping',
                    'info'
                ],
                'servidor_soap' => 'http://localhost/SistemaGaapem/api-soap/alumnos/servidor_soap_alumnos.php'
            ]);
            break;

        default:
            echo json_encode([
                'error' => 'Acción no válida',
                'acciones_disponibles' => [
                    'obtenerAlumnos',
                    'obtenerAlumno',
                    'crearAlumno',
                    'actualizarAlumno', 
                    'eliminarAlumno',
                    'obtenerAlumnosPorEspecialidad',
                    'ping',
                    'info'
                ]
            ]);
            break;
    }

} catch (SoapFault $e) {
    echo json_encode([
        'error' => 'Error en servicio SOAP',
        'codigo' => $e->faultcode,
        'mensaje' => $e->faultstring,
        'detalles' => $e->detail,
        'sugerencias' => [
            'Verifica que el servidor SOAP esté ejecutándose',
            'Confirma que la base de datos esté disponible',
            'Revisa la configuración de conexión',
            'Asegúrate de que PHP tenga SOAP habilitado'
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => 'Error general',
        'mensaje' => $e->getMessage(),
        'archivo' => basename($e->getFile()),
        'linea' => $e->getLine()
    ]);
}
?>