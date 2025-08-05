<!DOCTYPE html>
<html>
<head>
    <title>Agregar Nuevo Alumno</title>
    <!-- Incluye los estilos CSS necesarios -->
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/agregar.css">

    <!-- Otros estilos personalizados si los necesitas -->
</head>
<body>
    <div id="main">
        <div id="header">
            <h1 id="title">Alta De Alumno</h1>
           
        </div>
        <div id="content">
            <form id="formAltaAlumno">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
                <label for="apellidoPaterno">Apellido Paterno:</label>
                <input type="text" name="apellidoPaterno" required>
                <label for="apellidoMaterno">Apellido Materno:</label>
                <input type="text" name="apellidoMaterno" required>
                <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fechaNacimiento" required>
                <label for="fechaInicio">Fecha de Inicio:</label>
                <input type="date" name="fechaInicio" required>
                <label for="colegiatura">Colegiatura($):</label>
                <input type="number" name="colegiatura" required>
                <label for="especialidad">Especialidad:</label>
                <input type="text" name="especialidad" required>
                <label for="telefono">Telefono:</label>
                <input type="text" name="telefono" required>
                <label for="tipoDePago">Tipo de Pago:</label>
                <select name="tipoDePago" id="tipoDePago">
                <option value="">Selecciona una opción</option>
                <option value="colegiatura">Pago de Alta</option>
                <option value="cafeteria">Reinscripcion</option>
                </select>
                <label for="detalles">Detalles:</label>
                <input type="text" name="detalles" required>
                <label for="total">Costo de Alta($):</label>
                <input type="number" name="total" required>
                <input type="submit" value="Agregar">
                <a href="/SistemaGaapem/php/Alumnos/alumnos.php" class="cancel-button">Cancelar</a>
            </form>
        </div>
    </div>
    <script>
    document.getElementById("formAltaAlumno").addEventListener("submit", function(e) {
        e.preventDefault(); // evita envío tradicional

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch("http://localhost/SistemaGaapem/api-rest/alumnos/crear_alumno.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                alert("Alumno agregado correctamente");
                window.location.href = "/SistemaGaapem/php/Alumnos/alumnos.php";
            } else {
                alert("Error: " + response.error);
                console.error(response);
            }
        })
        .catch(err => {
            alert("Error al conectar con el servidor");
            console.error(err);
        });
    });
    </script>
</body>
</html>
