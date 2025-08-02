<link href="css/cssindex1.css" rel="stylesheet">

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
<!-- Botón para agregar nuevo registro -->
<!-- Incluir la librería QRCode.js -->
<script src="vendor/qr/qrcode.min.js"></script>

<!-- Botón para agregar nuevo registro -->
<!-- Tarjetas (Cards) -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Cantidad de Vacas</h5>
                <p class="card-text" id="cantidadVacas">0</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Leche Producida Esperada (Litros)</h5>
                <p class="card-text" id="cantidadLeche">0</p>
            </div>
        </div>
    </div>
</div>
<div class="button-container">
<!-- Botón para agregar nuevo registro -->
<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAgregar">
    Agregar Nuevo Registro
</button>
</div>
<!-- Tabla con el botón de generar QR -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Raza</th>
                <th>Utilidad</th>
                <th>Litros por Vaca Esperado</th>
                <th>Descendencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaRegistros">
            <!-- Las filas se cargarán dinámicamente aquí -->
        </tbody>
    </table>
</div>
<!-- Modal para agregar nuevo registro -->
<div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarLabel">Agregar Nuevo Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">
                    <div class="form-group">
                        <label for="raza">Raza</label>
                        <input type="text" class="form-control" id="raza" name="raza" required>
                    </div>
                    <div class="form-group">
                        <label for="utilidad">Utilidad</label>
                        <input type="text" class="form-control" id="utilidad" name="utilidad">
                        <small class="form-text text-muted">Deja este campo vacío si no tienes claro la utilidad.</small>
                    </div>
                    <div class="form-group">
                        <label for="litros_por_vaca">Litros por Vaca</label>
                        <input type="number" step="0.01" class="form-control" id="litros_por_vaca" name="litros_por_vaca">
                        <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
                    </div>
                    <div class="form-group">
                        <label for="descendencia">Descendencia</label>
                        <input type="number" class="form-control" id="descendencia" name="descendencia">
                        <small class="form-text text-muted">Deja este campo vacío si no tiene una descendencia.</small>
                    </div>
                    <input type="hidden" id="codigo" name="codigo">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardar" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar el QR -->
<script src="vendor/canva/html2canvas.min.js"></script>

<!-- Modal para mostrar el QR -->
<div class="modal fade" id="modalQR" tabindex="-1" role="dialog" aria-labelledby="modalQRLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalQRLabel">Código QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCode"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnDescargarQR" class="btn btn-primary">Descargar QR</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin del cont principal -->

<!-- jQuery y Bootstrap JS -->
<script src="vendor/jquery/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Abrir el modal para agregar una nueva vaca y limpiar el formulario
    $("#modalAgregar").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var id = button.data("id"); // Obtener el ID si existe (para edición)

        if (!id) {
            // Si es un nuevo registro, limpiar el formulario
            $("#formAgregar")[0].reset();
            $("#codigo").val("");
            $("#btnGuardar").off("click").click(agregarNuevaVaca);
        }
    });

    // Función para agregar una nueva vaca
    function agregarNuevaVaca() {
        var formData = {
            raza: $("#raza").val(),
            utilidad: $("#utilidad").val(),
            litros_por_vaca: $("#litros_por_vaca").val(),
            descendencia: $("#descendencia").val()
        };

        $.ajax({
            url: "../db/agregarregistro.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#modalAgregar").modal("hide");
                    alert(response.message);
                    $("#formAgregar")[0].reset();
                    cargarRegistros();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Error al guardar el registro: " + error);
            }
        });
    }

    // Función para cargar los registros en la tabla
   // Declarar la función en un ámbito global
function cargarRegistros() {
    $.ajax({
        url: "../db/cargarregistro.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            var tabla = $("#tablaRegistros");
            tabla.empty();

            var totalVacas = 0;
            var totalLeche = 0;

            response.forEach(function(registro) {
                var fila = `<tr>
                    <td data-label="ID">${registro.id}</td>
                    <td data-label="Código">${registro.codigo}</td>
                    <td data-label="Raza">${registro.raza}</td>
                    <td data-label="Utilidad">${registro.utilidad}</td>
                    <td data-label="Litros por Vaca">${registro.litros_por_vaca}</td>
                    <td data-label="Descendencia">${registro.descendencia}</td>
                    <td data-label="Acciones">
                        <button class="btn btn-sm btn-warning btn-editar" data-id="${registro.id}">Editar</button>
                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="${registro.id}">Eliminar</button>
                        <button class="btn btn-sm btn-info btn-generar-qr" data-codigo="${registro.codigo}">
                            <i class="fas fa-qrcode"></i> Generar QR
                        </button>
                    </td>
                </tr>`;
                tabla.append(fila);

                // Sumar la cantidad de vacas y litros de leche
                totalVacas += 1;
                totalLeche += parseFloat(registro.litros_por_vaca) || 0;
            });

            // Actualizar las tarjetas
            $("#cantidadVacas").text(totalVacas);
            $("#cantidadLeche").text(totalLeche.toFixed(2));
        },
        error: function(xhr, status, error) {
            alert("Error al cargar los registros: " + error);
        }
    });
}

$(document).ready(function() {
    // Cargar los registros al iniciar la página
    cargarRegistros();

    // Resto del código (agregar, editar, eliminar, generar QR, etc.)
    // ...
});

    // Manejar la acción de eliminar
    $(document).on("click", ".btn-eliminar", function() {
        var id = $(this).data("id");
        if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
            $.ajax({
                url: "../db/eliminarregistro.php",
                type: "POST",
                data: { id: id },
                success: function(response) {
                    var res = JSON.parse(response);
                    if (res.success) {
                        alert(res.message);
                        cargarRegistros();
                    } else {
                        alert(res.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error al eliminar el registro: " + error);
                }
            });
        }
    });

    // Manejar la acción de editar
    $(document).on("click", ".btn-editar", function() {
        var id = $(this).data("id");
        
        $.ajax({
            url: "../db/obtenerregistro.php",
            type: "GET",
            data: { id: id },
            dataType: "json",
            success: function(registro) {
                // Llenar el formulario con los datos del registro
                $("#modalAgregar").modal("show");
                $("#raza").val(registro.raza);
                $("#utilidad").val(registro.utilidad);
                $("#litros_por_vaca").val(registro.litros_por_vaca);
                $("#descendencia").val(registro.descendencia);
                $("#codigo").val(registro.codigo);

                // Asegurar que el botón "Guardar" solo maneje la actualización
                $("#btnGuardar").off("click").click(function() {
                    actualizarVaca(id);
                });
            },
            error: function(xhr, status, error) {
                alert("Error al obtener el registro: " + error);
            }
        });
    });

    // Función para actualizar una vaca
    function actualizarVaca(id) {
        var formData = {
            id: id,
            raza: $("#raza").val(),
            utilidad: $("#utilidad").val(),
            litros_por_vaca: $("#litros_por_vaca").val(),
            descendencia: $("#descendencia").val()
        };

        $.ajax({
            url: "../db/editarregistro.php",
            type: "POST",
            data: formData,
            success: function(response) {
                var res = JSON.parse(response);
                if (res.success) {
                    $("#modalAgregar").modal("hide");
                    alert(res.message);
                    cargarRegistros();
                } else {
                    alert(res.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Error al actualizar el registro: " + error);
            }
        });
    }

    // Cargar los registros al iniciar la página
    cargarRegistros();

    // Asignar la función al botón de generar QR
    $(document).on("click", ".btn-generar-qr", function() {
        var codigo = $(this).data("codigo");
        generarQR(codigo);
    });
});

// Función para generar QR
function generarQR(codigo) {
    $("#qrCode").empty(); // Limpiar el contenedor del QR

    new QRCode(document.getElementById("qrCode"), {
        text: codigo,
        width: 200,
        height: 200,
    });

    $("#modalQR").modal("show"); // Mostrar el modal
}

// Descargar QR como imagen
$("#btnDescargarQR").click(function() {
    html2canvas(document.querySelector("#qrCode")).then(canvas => {
        var link = document.createElement('a');
        link.download = 'QRCode.png';
        link.href = canvas.toDataURL();
        link.click();
    });
});

</script>

<?php require_once "vistas/parte_inferior.php" ?>