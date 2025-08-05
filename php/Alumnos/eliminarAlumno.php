<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

$id = $_POST['id'];

$sql = "DELETE FROM alumnos WHERE id = $id";

if ($conn->query($sql) === TRUE) {
  echo "Alumno eliminado exitosamente";
} else {
  echo "Error al eliminar al alumno: " . $conn->error;
}

$conn->close();
?>
