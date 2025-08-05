<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

$query = isset($_GET['q']) ? $_GET['q'] : '';
$especialidad = isset($_GET['especialidad']) ? $_GET['especialidad'] : '';

if (strlen($query) >= 2) {
    // Construir la consulta con filtro de especialidad si existe
    $sql = "SELECT id, CONCAT(nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombreCompleto, colegiatura, especialidad 
            FROM alumnos 
            WHERE CONCAT(nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) LIKE ?";
    
    $params = ["%$query%"];
    $types = "s";
    
    if (!empty($especialidad)) {
        $sql .= " AND especialidad = ?";
        $params[] = $especialidad;
        $types .= "s";
    }
    
    $sql .= " ORDER BY nombreCompleto LIMIT 15";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $alumnos = [];
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = [
            'id' => $row['id'],
            'text' => $row['nombreCompleto'], // Para Select2
            'colegiatura' => $row['colegiatura'],
            'especialidad' => $row['especialidad']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($alumnos);
} else {
    echo json_encode([]);
}
?>