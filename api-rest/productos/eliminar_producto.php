<?php
header("Content-Type: application/json");
include '../conexion.php'; // asegúrate que esta ruta apunta a tu conexión

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID del producto no proporcionado"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Producto eliminado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al eliminar el producto: " . $stmt->error]);
}

$stmt->close();
$conn->close();
