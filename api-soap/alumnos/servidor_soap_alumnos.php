<?php
// servidor_soap_alumnos.php - Servidor SOAP para gestión de alumnos

include 'C:/xampp/htdocs/SistemaGaapem/api-rest/conexion.php';

class AlumnoService {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Obtiene todos los alumnos
     * @return array Lista de alumnos
     */
    public function obtenerAlumnos() {
        $sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad FROM alumnos ORDER BY id asc";
        $resultado = mysqli_query($this->conn, $sql);

        if ($resultado === false) {
            return [
                "error" => "Error al ejecutar la consulta",
                "detalle" => mysqli_error($this->conn)
            ];
        }

        $alumnos = [];
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $alumnos[] = $fila;
        }

        return $alumnos;
    }

    /**
     * Obtiene un alumno por ID
     * @param int $id ID del alumno
     * @return array|string Alumno encontrado o mensaje de error
     */
    public function obtenerAlumno($id) {
        $sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad FROM alumnos WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            
            if ($fila = mysqli_fetch_assoc($resultado)) {
                return $fila;
            } else {
                return "Alumno no encontrado";
            }
        } else {
            return "Error en la consulta: " . mysqli_error($this->conn);
        }
    }

    /**
     * Crea un nuevo alumno
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param string $direccion
     * @param string $telefono
     * @param string $fechaInicio
     * @param float $colegiatura
     * @param string $especialidad
     * @return array|string Alumno creado o mensaje de error
     */
    public function crearAlumno($nombre, $apellidoPaterno, $apellidoMaterno, $direccion, $telefono, $fechaInicio, $colegiatura, $especialidad) {
        $sql = "INSERT INTO alumnos (nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssds", $nombre, $apellidoPaterno, $apellidoMaterno, $direccion, $telefono, $fechaInicio, $colegiatura, $especialidad);
            
            if (mysqli_stmt_execute($stmt)) {
                $id = mysqli_insert_id($this->conn);
                return $this->obtenerAlumno($id);
            } else {
                return "Error al crear alumno: " . mysqli_stmt_error($stmt);
            }
        } else {
            return "Error en la preparación: " . mysqli_error($this->conn);
        }
    }

    /**
     * Actualiza un alumno existente
     * @param int $id
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param string $direccion
     * @param string $telefono
     * @param string $fechaInicio
     * @param float $colegiatura
     * @param string $especialidad
     * @return array|string Alumno actualizado o mensaje de error
     */
    public function actualizarAlumno($id, $nombre, $apellidoPaterno, $apellidoMaterno, $direccion, $telefono, $fechaInicio, $colegiatura, $especialidad) {
        $sql = "UPDATE alumnos SET nombre = ?, apellidoPaterno = ?, apellidoMaterno = ?, direccion = ?, telefono = ?, fechaInicio = ?, colegiatura = ?, especialidad = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssdsi", $nombre, $apellidoPaterno, $apellidoMaterno, $direccion, $telefono, $fechaInicio, $colegiatura, $especialidad, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    return $this->obtenerAlumno($id);
                } else {
                    return "Alumno no encontrado o sin cambios";
                }
            } else {
                return "Error al actualizar alumno: " . mysqli_stmt_error($stmt);
            }
        } else {
            return "Error en la preparación: " . mysqli_error($this->conn);
        }
    }

    /**
     * Elimina un alumno
     * @param int $id ID del alumno
     * @return string Mensaje de confirmación
     */
    public function eliminarAlumno($id) {
        $sql = "DELETE FROM alumnos WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    return "Alumno eliminado correctamente";
                } else {
                    return "Alumno no encontrado";
                }
            } else {
                return "Error al eliminar alumno: " . mysqli_stmt_error($stmt);
            }
        } else {
            return "Error en la preparación: " . mysqli_error($this->conn);
        }
    }

    /**
     * Busca alumnos por especialidad
     * @param string $especialidad
     * @return array Lista de alumnos de la especialidad
     */
    public function obtenerAlumnosPorEspecialidad($especialidad) {
        $sql = "SELECT id, nombre, apellidoPaterno, apellidoMaterno, direccion, telefono, fechaInicio, colegiatura, especialidad FROM alumnos WHERE especialidad = ? ORDER BY apellidoPaterno, apellidoMaterno, nombre";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $especialidad);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            
            $alumnos = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $alumnos[] = $fila;
            }
            
            return $alumnos;
        } else {
            return ["error" => "Error en la consulta: " . mysqli_error($this->conn)];
        }
    }
}

// Configuración del servidor SOAP
try {
    // Creamos el servidor SOAP
    $server = new SoapServer(null, ['uri' => 'http://localhost/SistemaGaapem/api-soap/alumnos']);

    // Asociamos la clase de servicio
    $server->setClass('AlumnoService');

    // Manejamos las peticiones SOAP
    $server->handle();

} catch (SoapFault $e) {
    echo "Error en el servidor SOAP: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error general: " . $e->getMessage();
}
?>