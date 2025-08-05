<?php
// Agrega este bloque de código PHP al principio del archivo
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

// Consulta para obtener los productos con cantidad menor o igual a 1 y departamento diferente de ESCOLARES
$queryProductosAgotados = "SELECT nombre, cantidad, departamento FROM productos WHERE cantidad <= 1 AND departamento <> 'ESCOLARES'";
$resultProductosAgotados = $conn->query($queryProductosAgotados);

$productosAgotados = [];

if ($resultProductosAgotados->num_rows > 0) {
    while ($row = $resultProductosAgotados->fetch_assoc()) {
        $productosAgotados[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema Cafeteria</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/formularios.css">
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
            <h1 id="title">Cafeteria</h1>
            <img id="logo" src="/SistemaGaapem/src/LogoSoft.jpeg" alt="Logo">
        </div>
        <div id="content">
            <button onclick="document.getElementById('formularioAgregar').style.display='block'">Agregar nuevo producto</button>
            <input class="inputFiltro" type="text" id="filtroProducto" placeholder="Filtrar por nombre">
            <button onclick="filtrarPorProducto()">Filtrar</button>
            <button onclick="location.reload();">Recargar Tabla</button>
            <!-- Agrega el botón "venta" después de los otros botones -->
            <button onclick="location.href='/SistemaGaapem/php/Factura.php'">Venta</button>

            <div id="formularioAgregar" style="display:none;">
                <form action="agregarProducto.php" method="post">
                    <label for="nombre">Nombre:</label><br>
                    <input type="text" id="nombre" name="nombre"><br>
                    <label for="cantidad">Cantidad:</label><br>
                    <input type="number" id="cantidad" name="cantidad"><br>
                    <label for="precio">Precio:</label><br>
                    <input type="number" id="precio" name="precio"><br>
                    <label for="departamento">Departamento:</label>
                    <select name="departamento" id="departamento">
                        <option value="">Selecciona una opción</option>
                        <option value="papas">PAPAS</option>
                        <option value="kits">ESCOLARES</option>
                        <option value="galletas">GALLETAS</option>
                        <option value="refrescos">REFRESCOS</option>
                        <option value="dulces">DULCES</option>
                        <option value="aguas">AGUAS</option>
                        <option value="otros">OTROS</option>
                    </select>
                    
                    <input type="submit" value="Agregar">
                    <button type="button" onclick="cerrarFormulario('formularioAgregar')">Cancelar</button>
                </form>
            </div>

            <form id="formularioActualizar" action="actualizarCaf.php" method="post" style="display:none;">
                <input type="hidden" name="id" id="update-id">
                <label for="update-nombre">Nombre:</label><br>
                <input type="text" id="update-nombre" name="nombre"><br>
                <label for="update-cantidad">Cantidad:</label><br>
                <input type="number" id="update-cantidad" name="cantidad"><br>
                <label for="update-precio">Precio:</label><br>
                <input type="number" id="update-precio" name="precio"><br>
                <label for="">Selecciona el departamento:</label>
                <select name="departamento" id="update-departamento" >
                <option value="">Selecciona una opción</option>
                <option value="GALLETAS">GALLETAS</option>
                <option value="REFRESCOS">REFRESCOS</option>
                <option value="PAPAS">PAPAS</option>
                <option value="ESCOLARES">ESCOLARES</option>
                <option value="DULCES">DULCES</option>
                <option value="COSMETICOS">COSMETICOS</option>
                <option value="KITS">KITS</option>
                <option value="SOPAS">SOPAS</option>
                </select>
                <input type="submit" value="Actualizar">
                <button type="button" onclick="cerrarFormulario('formularioActualizar')">Cancelar</button>

            </form>

            <?php
                include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

                $sql = "SELECT id, nombre, precio, cantidad, departamento FROM productos ORDER BY nombre ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table id='miTabla' class='miClase' ><tr><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Departamento</th><th>Acciones</th></tr>";
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row["nombre"]."</td><td>$".$row["precio"]."</td><td>".$row["cantidad"]."</td><td>".$row["departamento"]."</td><td><img src='/SistemaGaapem/src/eliminarIcon.png' alt='Eliminar' style='max-width: 20px;' data-id='".$row["id"]."' onclick='confirmDelete(\"".$row["nombre"]."\", this.getAttribute(\"data-id\"))'><img src='/SistemaGaapem/src/img/actualizarIcon.png' alt='Actualizar' style='max-width: 20px;' data-id='".$row["id"]."' onclick='confirmUpdate(\"".$row["id"]."\", \"".$row["nombre"]."\", \"".$row["cantidad"]."\", \"".$row["precio"]."\", \"".$row["departamento"]."\"),document.documentElement.scrollIntoView()'></td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 resultados";
                }
                $conn->close();
            ?>
        </div>
    </div>
    <!-- Ventana flotante -->
<div id="fondoSemitransparente"></div>
<div id="ventanaFlotante">
    <span id="cerrarVentanaFlotante" onclick="cerrarVentanaFlotante()">X</span>
    <div id="contenidoVentana"></div>
</div>
<div id="modal" class="modal">
    <div class="modal-content">
        <p id="modalText"></p>
        <button id="confirmBtn">Eliminar</button>
        <button onclick="modal.style.display='none'">Cancelar</button>
    </div>
</div>

    <script>
        function confirmUpdate(id, nombre, cantidad, precio, departamento) {
            // Llena el formulario con la información actual del producto
            document.getElementById("update-id").value = id;
            document.getElementById("update-nombre").value = nombre;
            document.getElementById("update-cantidad").value = cantidad;
            document.getElementById("update-precio").value = precio;
            document.getElementById("update-departamento").value = departamento;

            // Muestra el formulario
            document.getElementById("formularioActualizar").style.display = "block";
        }
        function cerrarFormulario(formularioId) {
        // Oculta el formulario
        document.getElementById(formularioId).style.display = "none";
    }
    function mostrarProductosAgotados() {
        var productosAgotados = <?php echo json_encode($productosAgotados); ?>;

        if (productosAgotados.length > 0) {
            var ventanaFlotante = document.getElementById("ventanaFlotante");
            var fondoSemitransparente = document.getElementById("fondoSemitransparente");
            var contenidoVentana = document.getElementById("contenidoVentana");

            // Construye el mensaje con información de productos agotados
            var mensaje = "Productos proximos a agotarse:<br><ul>";

            for (var i = 0; i < productosAgotados.length; i++) {
                mensaje += "<li><strong>" + productosAgotados[i].nombre + ", <strong>Cantidad actual:</strong> " + productosAgotados[i].cantidad + "</li>";
            }

            mensaje += "</ul>";

            // Actualiza el contenido de la ventana flotante
            contenidoVentana.innerHTML = mensaje;

            // Muestra la ventana flotante y el fondo semitransparente
            ventanaFlotante.style.display = "block";
            fondoSemitransparente.style.display = "block";
        }
    }

    function cerrarVentanaFlotante() {
        var ventanaFlotante = document.getElementById("ventanaFlotante");
        var fondoSemitransparente = document.getElementById("fondoSemitransparente");

        // Oculta la ventana flotante y el fondo semitransparente
        ventanaFlotante.style.display = "none";
        fondoSemitransparente.style.display = "none";
    }
    function filtrarPorProducto() {
        var producto = document.getElementById("filtroProducto").value;
        $.ajax({
            url: 'filtrarCaf.php',
            type: 'post',
            data: {producto: producto},
            success: function(response) {
                // Reemplaza el contenido de la tabla con los nuevos resultados
                $('#miTabla').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
            }
        });
    }
    window.onload = mostrarProductosAgotados;
    </script>
    <script>
        const modal = document.getElementById("modal");
const confirmBtn = document.getElementById("confirmBtn");

function confirmDelete(nombreProducto, id) {
    document.getElementById("modalText").innerText = `¿Estás seguro de eliminar "${nombreProducto}"?`;
    modal.style.display = "block";

    confirmBtn.onclick = function () {
        modal.style.display = "none";
        deleteProducto(id);
    };
}

function deleteProducto(id) {
    fetch("/SistemaGaapem/api-rest/productos/eliminar_producto.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // o elimina solo la fila desde el DOM si prefieres
        } else {
            alert("Error: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Ocurrió un error al eliminar el producto.");
    });
}

    </script>
    <script src="/script.js"></script>
</body>
</html>
