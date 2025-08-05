<?php
header("Content-Type: application/json");
include '../conexion.php'; // ajusta ruta si es distinta

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido, usa DELETE"]);
    exit;
}

// Obtener ID: primero de query string, si no está, intentar del body JSON
$id = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? intval($data['id']) : null;
}

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "ID del producto no proporcionado"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Producto eliminado correctamente"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Producto no encontrado"]);
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al eliminar el producto: " . $stmt->error]);
}

$stmt->close();
$conn->close();
