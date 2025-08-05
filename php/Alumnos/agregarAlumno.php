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
            <h1 id="title">Agregar Nuevo Alumno</h1>
           
        </div>
        <div id="content">
            <form id="formAgregarAlumno">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>

                <label for="direccion">direccion:</label>
                <input type="text" name="direccion" required>

                <label for="fechaInicio">Fecha de Inicio:</label>

                <input type="date" name="fechaInicio" required>

                <label for="colegiatura">Colegiatura:</label>

                <input type="number" name="colegiatura" required>

                <label for="selecEsp">Selecciona la especialidad:</label>
                <select name="especialidad" id="especialidad" >
                <option value="">Selecciona una opción</option>
                <option value="Informatica">Informatica</option>
                <option value="Estilismo">Estilismo</option>
                <option value="Barberia">Barberia</option>
                <option value="Gatronomia">Gastronomia</option>
                </select>

                <label for="telefono">Telefono:</label>
                <input type="text" name="telefono" required>

                <input type="submit" value="Agregar">
                <a href="/SistemaGaapem/php/Alumnos/alumnos.php" class="cancel-button">Cancelar</a>

            </form>
        </div>
    </div>
    <script>
    document.getElementById("formAgregarAlumno").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const jsonData = Object.fromEntries(formData.entries());

        fetch("http://localhost/SistemaGaapem/api-rest/alumnos/crear_alumno.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(jsonData)
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                alert("Alumno agregado correctamente");
                window.location.href = "/SistemaGaapem/php/Alumnos/alumnos.php";
            } else {
                alert("Error: " + response.error);
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
