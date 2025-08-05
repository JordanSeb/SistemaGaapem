<?php
header("Content-Type: application/json");
include 'C:/xampp/htdocs/SistemaGaapem/api-rest/conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Validación básica
$campos = ['nombre', 'direccion', 'fechaInicio', 'colegiatura', 'especialidad', 'telefono'];
foreach ($campos as $campo) {
    if (empty($data[$campo])) {
        http_response_code(400);
        echo json_encode(["error" => "Falta el campo: $campo"]);
        exit;
    }
}

// Separar nombre completo
$nombreArray = explode(" ", $data["nombre"]);
$nombre = $nombreArray[0];
$apellidoPaterno = $nombreArray[1] ?? '';
$apellidoMaterno = $nombreArray[2] ?? '';

// Insertar en base de datos
$stmt = $conn->prepare("INSERT INTO alumnos (nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssis", 
    $nombre,
    $apellidoPaterno,
    $apellidoMaterno,
    $data["direccion"],
    $data["telefono"],
    $data["fechaInicio"],
    $data["colegiatura"],
    $data["especialidad"]
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Alumno agregado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al insertar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
