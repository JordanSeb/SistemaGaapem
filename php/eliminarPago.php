<?php
include 'Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "DELETE FROM pagos WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error: " . $conn->error;
    } else {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Pago eliminado exitosamente";
        } else {
            echo "Error al eliminar el pago: " . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
}
?>
