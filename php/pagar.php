<?php
include 'C:/xampp/htdocs/SistemaGaapem/php/Conexion.php';

// Consulta para obtener los alumnos
$queryAlumnos = "SELECT id, CONCAT(nombre, ' ', apellidoPaterno, ' ', apellidoMaterno) AS nombreCompleto, colegiatura, especialidad FROM alumnos ORDER BY nombreCompleto";
$resultAlumnos = mysqli_query($conn, $queryAlumnos);

// Consulta para obtener las especialidades
$queryEspecialidades = "SELECT DISTINCT especialidad FROM alumnos";
$resultEspecialidades = mysqli_query($conn, $queryEspecialidades);

// Consulta para obtener los productos de la cafetería
$queryProductos = "SELECT * FROM productos";
$resultProductos = mysqli_query($conn, $queryProductos);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Pagos</title>
    <link rel="stylesheet" type="text/css" href="/SistemaGaapem/styles/PagoPrevio.css">
    <!-- Agregar jQuery ANTES de Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .seleccionado {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <form action="procesar_pago.php" method="post" id="formularioPago">
        <label for="tipoDePago">Tipo de Pago:</label>
        <select name="tipoDePago" id="tipoDePago" onchange="mostrarCampos(this.value)">
            <option value="">Selecciona una opción</option>
            <option value="colegiatura">Colegiatura</option>
            <option value="cafeteria">Productos, Kits, Manuales</option>
            <option value="honorarios">Honorarios o Salidas</option>
        </select>
        <br/>
        
        <div id="colegiatura" style="display: none;">
            <label for="especialidad">Selecciona la especialidad:</label>
            <select name="especialidad" id="especialidad" onchange="actualizarBusquedaAlumnos()">
                <option value="">Cualquiera</option>
                <?php
                // Reiniciar el resultado de especialidades
                mysqli_data_seek($resultEspecialidades, 0);
                while($rowEspecialidad = mysqli_fetch_assoc($resultEspecialidades)) {
                    echo '<option value="'.$rowEspecialidad['especialidad'].'">'.$rowEspecialidad['especialidad'].'</option>';
                }
                ?>
            </select>
            <br/>
            
            <label for="alumno">Alumno:</label>
            <select name="alumno_id" id="alumno" style="width: 100%;">
                <option value="">Selecciona un alumno...</option>
            </select>
            <br/>
            <br>
            <label for="colegiaturaInput">Colegiatura:</label>
            <input type="hidden" id="nombreAlumno" name="nombreAlumno" value="">
            <input type="hidden" id="detallesProductos" name="detallesProductos" value="">
            <input type="number" id="colegiaturaInput" name="colegiaturaInput">
        </div>
        
        <div id="cafeteria" style="display: none;">
            <label for="busquedaProductos">Buscar Productos:</label>
            <input type="text" id="busquedaProductos" oninput="filtrarProductos()">
            
            <label for="productosCafeteria">Productos de Cafetería:</label>
            <select id="productosCafeteria" multiple></select>
            <button type="button" onclick="agregarProducto()">Agregar Producto</button>
            <br/>
            
            <div id="resumenCafeteria">
                <h3>Resumen de Cafetería</h3>
                <ul id="listaProductos"></ul>
                <p>Total: $<span id="totalCafeteria">0.00</span></p>
            </div>
            
            <div class="checkbox-container">
                <input type="checkbox" id="ocultarCampos" onchange="mostrarOcultarCampos()">
                <label for="ocultarCampos">Ocultar campos</label>
            </div>
            
            <div id="contenedorCamposPago">
                <label for="precioPagado">Precio Pagado:</label>
                <input type="number" id="precioPagado" name="precioPagado" value="0">

                <label for="porPagar">Por Pagar:</label>
                <input type="number" id="porPagar" name="porPagar" value="0">
            </div>
            
            <label for="total">Total:</label>
            <input type="number" id="total" name="total">
        </div>
        
        <div id="honorarios" style="display: none;">
            <label for="honorarios">Cantidad:</label>
            <textarea id="honorarios" name="honorarios"></textarea>
        </div>
        
        <label for="detalles">Detalles:</label>
        <textarea id="detalles" name="detalles"></textarea>
        <br>

        <input type="submit" value="Pagar">
        <a href="/SistemaGaapem/php/index.php" class="cancel-button">Cancelar</a>
    </form>

    <script>
        // Variables globales
        var productosCafeteria = <?php echo json_encode(mysqli_fetch_all($resultProductos, MYSQLI_ASSOC)); ?>;
        var productosSeleccionados = [];
        var detalleCafeteria = "";
        var select2Alumno = null;

        $(document).ready(function() {
            // Inicializar Select2
            inicializarSelect2();
            
            // Configurar eventos
            actualizarPorPagar();
            
            // Cargar productos en la cafetería
            cargarTodosLosProductos();
        });

        function inicializarSelect2() {
            select2Alumno = $('#alumno').select2({
                placeholder: "Buscar alumno...",
                minimumInputLength: 2,
                ajax: {
                    url: 'Alumnos/buscar_alumnos.php',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var especialidadSeleccionada = $('#especialidad').val();
                        return {
                            q: params.term,
                            especialidad: especialidadSeleccionada
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(alumno) {
                                return {
                                    id: alumno.id,
                                    text: alumno.text,
                                    colegiatura: alumno.colegiatura,
                                    especialidad: alumno.especialidad
                                };
                            })
                        };
                    }
                }
            });

            // Evento cuando se selecciona un alumno
            $('#alumno').on('select2:select', function (e) {
                var data = e.params.data;
                document.getElementById("colegiaturaInput").value = data.colegiatura;
                document.getElementById("nombreAlumno").value = data.text;
            });
        }

        function actualizarBusquedaAlumnos() {
            // Limpiar selección actual
            $('#alumno').val(null).trigger('change');
            
            // El filtro se aplicará automáticamente en la próxima búsqueda
            // gracias al parámetro especialidad en la función data de Select2
        }

        function mostrarCampos(tipoDePago) {
            var colegiaturaDiv = document.getElementById("colegiatura");
            colegiaturaDiv.style.display = (tipoDePago == "colegiatura") ? "block" : "none";
            
            document.getElementById("cafeteria").style.display = (tipoDePago == "cafeteria") ? "block" : "none";
            document.getElementById("honorarios").style.display = (tipoDePago == "honorarios") ? "block" : "none";
        }

        function cargarTodosLosProductos() {
            var selectProductos = document.getElementById("productosCafeteria");
            selectProductos.innerHTML = "";

            productosCafeteria.forEach(function(producto) {
                var option = document.createElement("option");
                option.value = producto.id;
                option.setAttribute("data-id", producto.id);
                option.setAttribute("data-precio", producto.precio);
                option.setAttribute("data-nombre", producto.nombre);
                option.textContent = producto.nombre + " - $" + producto.precio;
                selectProductos.appendChild(option);
            });
        }

        function filtrarProductos() {
            var busqueda = document.getElementById("busquedaProductos").value.toLowerCase();
            var selectProductos = document.getElementById("productosCafeteria");
            selectProductos.innerHTML = "";

            productosCafeteria.forEach(function(producto) {
                if (producto.nombre.toLowerCase().includes(busqueda)) {
                    var option = document.createElement("option");
                    option.value = producto.id;
                    option.setAttribute("data-nombre", producto.nombre);
                    option.setAttribute("data-precio", producto.precio);
                    option.textContent = producto.nombre + " - $" + producto.precio;
                    selectProductos.appendChild(option);
                }
            });
        }

        function agregarProducto() {
            var selectProductos = document.getElementById("productosCafeteria");
            var selectedOptions = selectProductos.selectedOptions;

            for (var i = 0; i < selectedOptions.length; i++) {
                var id = selectedOptions[i].value;
                var nombre = selectedOptions[i].getAttribute("data-nombre");
                var precio = parseFloat(selectedOptions[i].getAttribute("data-precio"));

                var productoExistente = productosSeleccionados.find(function(item) {
                    return item.id === id;
                });

                if (productoExistente) {
                    productoExistente.cantidad++;
                } else {
                    productosSeleccionados.push({ id: id, nombre: nombre, precio: precio, cantidad: 1 });
                }
            }

            actualizarResumenCafeteria();
        }

        function eliminarProducto(index) {
            var producto = productosSeleccionados[index];
            if (producto.cantidad > 1) {
                producto.cantidad--;
            } else {
                productosSeleccionados.splice(index, 1);
            }
            actualizarResumenCafeteria();
        }

        function actualizarResumenCafeteria() {
            var listaProductos = document.getElementById("listaProductos");
            var totalCafeteria = document.getElementById("totalCafeteria");

            listaProductos.innerHTML = "";
            detalleCafeteria = "";

            var total = 0;
            for (var i = 0; i < productosSeleccionados.length; i++) {
                var producto = productosSeleccionados[i];
                var li = document.createElement("li");
                li.textContent = producto.nombre + " x" + producto.cantidad + ": $" + (producto.precio * producto.cantidad).toFixed(2);

                var btnEliminar = document.createElement("button");
                btnEliminar.textContent = "Eliminar";
                btnEliminar.onclick = (function(index) {
                    return function() {
                        eliminarProducto(index);
                    };
                })(i);
                li.appendChild(btnEliminar);

                li.className = "seleccionado";
                listaProductos.appendChild(li);

                total += producto.precio * producto.cantidad;
                detalleCafeteria += producto.nombre + " x" + producto.cantidad + ", ";
            }

            detalleCafeteria = detalleCafeteria.slice(0, -2);
            totalCafeteria.textContent = total.toFixed(2);
            document.getElementById("total").value = total.toFixed(2);
            document.getElementById("detallesProductos").value = JSON.stringify(productosSeleccionados);

            let precioPagado = parseFloat(document.getElementById("precioPagado").value);
            let porPagar = total - precioPagado;
            document.getElementById("porPagar").value = porPagar.toFixed(2);
        }

        function actualizarPorPagar() {
            const precioPagadoInput = document.getElementById("precioPagado");
            
            precioPagadoInput.addEventListener("input", function() {
                const total = parseFloat(document.getElementById("total").value) || 0;
                const pagado = parseFloat(precioPagadoInput.value) || 0;
                const porPagar = total - pagado;

                document.getElementById("porPagar").value = porPagar.toFixed(2);
            });
        }

        function mostrarOcultarCampos() {
            var checkbox = document.getElementById("ocultarCampos");
            var contenedorCamposPago = document.getElementById("contenedorCamposPago");

            if (checkbox.checked) {
                contenedorCamposPago.style.display = "none";
            } else {
                contenedorCamposPago.style.display = "block";
            }
        }
    </script>
</body>
</html>
