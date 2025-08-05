<?php
header("Content-Type: application/json");
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Obtener ID desde JSON
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID del alumno no proporcionado"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM alumnos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Alumno eliminado exitosamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al eliminar el alumno: " . $stmt->error]);
}

$stmt->close();
$conn->close();
