<link rel="stylesheet" href="css/estadistica.css">
<?php
session_start();

// Verificar si la sesión está activa y si la cédula está en la sesión
if (!isset($_SESSION["s_username"]) || $_SESSION["s_username"] === null) {
    header("Location: ../../index.php");
    exit();
}

// Recuperamos la cédula de la sesión
$Cedula = $_SESSION["s_cedula"];  // Usamos $_SESSION["s_cedula"] para la cédula
?>

<?php require_once "vistas/parte_superior.php" ?>

<!-- Contenedor principal -->
<div class="container-fluid">
    <!-- Filtros de búsqueda -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="filtroFecha">Filtrar por fecha:</label>
            <input type="date" id="filtroFecha" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="filtroVaca">Filtrar por vaca:</label>
            <select id="filtroVaca" class="form-control">
                <option value="">Todas las vacas</option>
                <!-- Las opciones se llenarán dinámicamente con JavaScript -->
            </select>
        </div>
        <div class="col-md-4">
            <button id="btnFiltrar" class="btn btn-primary mt-4">Filtrar</button>
        </div>
    </div>

    <!-- Tarjetas (Cards) -->
    <div class="row mb-4" id="cardsContainer">
        <!-- Las tarjetas se llenarán dinámicamente con JavaScript -->
    </div>

    <!-- Tabla de registros -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código Vaca</th>
                    <th>Promedio Leche</th>
                    <th>Descendencia Real</th>
                    <th>Utilidad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody id="tablaRegistros">
                <!-- Las filas se cargarán dinámicamente aquí -->
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery y Bootstrap JS -->
<script src="vendor/jquery/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Función para cargar los registros y actualizar las tarjetas
    function cargarRegistros(filtroFecha = "", filtroVaca = "") {
        $.ajax({
            url: "../db/cargar_promedio.php", // Endpoint para cargar los datos de la tabla "promedio"
            type: "GET",
            data: { fecha: filtroFecha, codigo_vaca: filtroVaca },
            dataType: "json",
            success: function(response) {
                // Limpiar la tabla y las tarjetas
                $("#tablaRegistros").empty();
                $("#cardsContainer").empty();

                var totalPromedioLeche = 0;
                var totalDescendencia = 0;
                var totalUtilidad = 0;

                // Llenar la tabla y calcular totales para las tarjetas
                response.forEach(function(registro) {
                    var fila = `<tr>
                        <td>${registro.id}</td>
                        <td>${registro.codigo_vaca}</td>
                        <td>${registro.promedio_leche}</td>
                        <td>${registro.descendencia_real}</td>
                        <td>${registro.utilidad}</td>
                        <td>${registro.fecha}</td>
                    </tr>`;
                    $("#tablaRegistros").append(fila);

                    // Sumar valores para las tarjetas
                    totalPromedioLeche += parseFloat(registro.promedio_leche) || 0;
                    totalDescendencia += parseInt(registro.descendencia_real) || 0;
                    totalUtilidad += parseFloat(registro.utilidad) || 0;
                });

                // Actualizar las tarjetas
                $("#cardsContainer").html(`
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Promedio de Leche</h5>
                                <p class="card-text">${totalPromedioLeche.toFixed(2)} litros</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Descendencia Real</h5>
                                <p class="card-text">${totalDescendencia}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Utilidad Total</h5>
                                <p class="card-text">${totalUtilidad.toFixed(2)}</p>
                            </div>
                        </div>
                    </div>
                `);
            },
            error: function(xhr, status, error) {
                alert("Error al cargar los registros: " + error);
            }
        });
    }

    // Cargar registros al iniciar la página
    cargarRegistros();

    // Manejar el evento de filtrar
    $("#btnFiltrar").click(function() {
        var filtroFecha = $("#filtroFecha").val();
        var filtroVaca = $("#filtroVaca").val();
        cargarRegistros(filtroFecha, filtroVaca);
    });

    // Cargar opciones de vacas en el filtro
    $.ajax({
        url: "../db/cargar_vacas_promedio.php", // Endpoint para obtener las vacas
        type: "GET",
        dataType: "json",
        success: function(response) {
            response.forEach(function(vaca) {
                $("#filtroVaca").append(`<option value="${vaca.codigo_vaca}">${vaca.codigo_vaca}</option>`);
            });
        },
        error: function(xhr, status, error) {
            alert("Error al cargar las vacas: " + error);
        }
    });
});
</script>

<?php require_once "vistas/parte_inferior.php" ?>