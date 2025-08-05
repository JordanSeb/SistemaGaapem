<?php
header("Content-Type: application/json");
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$nombre = $data['nombre'] ?? null;
$cantidad = $data['cantidad'] ?? null;
$precio = $data['precio'] ?? null;
$departamento = $data['departamento'] ?? null;

if (!$id || !$nombre || !$cantidad || !$precio || !$departamento) {
    http_response_code(400);
    echo json_encode(["error" => "Todos los campos son obligatorios"]);
    exit;
}

$stmt = $conn->prepare("UPDATE productos SET nombre='$nombre',cantidad=$cantidad, precio=$precio, departamento='$departamento' WHERE id=$id");

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Producto actualizado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al agregar producto: " . $stmt->error]);
}

$stmt->close();
$conn->close();
