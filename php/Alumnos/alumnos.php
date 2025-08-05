<!DOCTYPE html>
<html>
<head>
    <title>Sistema Alumnos</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/tabla.css">
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/modal.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
    <div id="sidebar">
        <a class="menu-link" href="/SistemaGaapem/php/index.php">Inicio</a>
        <a class="menu-link" href="/SistemaGaapem/php/Alumnos/alumnos.php">Alumnos</a>
        <a class="menu-link" href="/SistemaGaapem/php/Cafeteria/pagos.php">Cafeteria</a>
    </div>
    <div id="main">
        <div id="header">
            <h1 id="title">Alumnos</h1>
            <img id="logo" src="/SistemaGaapem/src/LogoSoft.jpeg" alt="Logo">
        </div>
        <div id="content">
            
<a href="agregarAlumno.php" class="agregar-button">Agregar Nuevo Alumno</a>
<input type="text" id="filtroEspecialidad" placeholder="Filtrar por nombre">
        <button onclick="filtrarPorEspecialidad()">Filtrar</button>
        <button onclick="location.reload();">Recargar Tabla</button>
        <a href="altaAlumno.php" class="alta-button">Alta Alumno</a>
        <button onclick="window.print()">Imprimir Datos De Alumnos</button>

        <table id="miTabla" class="miClase">
        <thead>
            <tr>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Fecha de Inicio</th>
            <th>Colegiatura</th>
            <th>Especialidad</th>
            <th>Actualizar</th>
            <th>Eliminar</th>
            </tr>
        </thead>
        <tbody></tbody>
        </table>

            <div id="myModal" class="modal">
        <div class="modal-content">
            <h2>Confirmar eliminación</h2>
            <p id="modalText">¿Estás seguro de que quieres eliminar a este alumno?</p>
            <button id="confirmBtn">Confirmar</button>
            <button id="cancelBtn">Cancelar</button>
        </div>

    </div>
    <script>


        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var confirmBtn = document.getElementById("confirmBtn");
        var cancelBtn = document.getElementById("cancelBtn");

        function confirmDelete(alumno) {
            document.getElementById("modalText").innerHTML = "¿Estás seguro de que quieres eliminar a " + alumno + "?";
            modal.style.display = "block";
        }

        confirmBtn.onclick = function() {
            modal.style.display = "none";
            // Aquí puedes agregar el código para eliminar al alumno
        }

        cancelBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        function filtrarPorEspecialidad() {
    var nombre = document.getElementById("filtroEspecialidad").value;
    $.ajax({
        url: 'filtrar.php',
        type: 'post',
        data: {nombre: nombre},
        success: function(response) {
            // Reemplaza el contenido de la tabla con los nuevos resultados
            $('#miTabla').html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
        }
    });
}



        
    </script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/SistemaGaapem/api-rest/alumnos/obtener_alumnos.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var tabla = $('#miTabla tbody');
                    tabla.empty(); // limpia por si acaso
                    data.forEach(function(alumno) {
                        console.log(alumno.fechaInicio);
                        var fila = `
                            <tr>
                                <td>${alumno.nombre}</td>
                                <td>${alumno.apellidoPaterno}</td>
                                <td>${alumno.apellidoMaterno}</td>
                                <td>${alumno.direccion}</td>
                                <td>${alumno.telefono}</td>
                                <td>${alumno.fechaInicio}</td>
                                <td>$${alumno.colegiatura}</td>
                                <td>${alumno.especialidad}</td>
                                <td><a href='actualizarAlumno.php?id=${alumno.id}'>Actualizar</a></td>
                                <td><img src='/SistemaGaapem/src/eliminarIcon.png' alt='Eliminar' style='max-width: 20px;' onclick='confirmDelete("${alumno.nombre} ${alumno.apellidoPaterno} ${alumno.apellidoMaterno}", ${alumno.id})'></td>
                            </tr>
                        `;
                        tabla.append(fila);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar alumnos:", error);
                }
            });
        });
    </script>

    </div>

</div>
<script src="/SistemaGaapem/script.js"></script>
</body>
</html>
