
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

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escanear QR</title>
  <script src="vendor/jquery/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="css/cssbusqueda.css">
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <script src="assets/plugins/qrCode.min.js"></script>
  <!-- jQuery (si lo usas) -->



  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .centered {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
    .qr-image {
      margin: 20px 0;
    }
    .search-container {
      margin-top: 20px;
    }
    .btn-container {
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-sm-6 shadow p-3 centered">
        <h5 class="text-center">Escanear código QR</h5>
        <div class="qr-image">
          <a id="btn-scan-qr" href="#">
            <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg" class="img-fluid" width="175">
          </a>
          <canvas hidden="" id="qr-canvas" class="img-fluid"></canvas>
        </div>
        <div class="btn-container">
          <button class="btn btn-success btn-sm rounded-3 " onclick="encenderCamara()">Encender cámara</button>
          <button class="btn btn-danger btn-sm rounded-3" onclick="cerrarCamara()">Detener cámara</button>
        </div>
      </div>
    </div>

    <div class="row justify-content-center mt-3">
      <div class="col-sm-6 shadow p-3 centered search-container">
        <h5 class="text-center">Buscar por Código</h5>
        <div class="row text-center">
          <input type="text" id="searchInput" class="form-control text-center" placeholder="Ingrese código..." onkeyup="buscarPorCodigo()">
        </div>
        <div class="btn-container">
          <button class="btn btn-primary btn-sm rounded-3 mb-2" onclick="buscarPorCodigo()">Buscar</button>
        </div>
      </div>
    </div>

    

  <!-- Modal para agregar datos -->
<!-- Modal para agregar datos -->
<div class="modal fade" id="modalAgregarDatos" tabindex="-1" aria-labelledby="modalAgregarDatosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarDatosLabel">Agregar Datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAgregarDatos">
          <input type="hidden" id="codigoVaca" name="codigoVaca">
          <div class="mb-3">
            <label for="descendencia" class="form-label">Descendencia</label>
            <input type="number" class="form-control" id="descendencia" name="descendencia" required>
            <small class="form-text text-muted">Deja este campo vacío si no tienes un dato que agregar.</small>
          </div>
          <div class="mb-3">
            <label for="promedioLeche" class="form-label">Promedio de Leche</label>
            <input type="number" step="0.01" class="form-control" id="promedioLeche" name="promedioLeche" required>
            <small class="form-text text-muted">Deja este campo vacío si no tienes un dato que agregar.</small>
          </div>
          <div class="mb-3">
            <label for="utilidad" class="form-label">Utilidad</label>
            <input type="text" class="form-control" id="utilidad" name="utilidad" required>
            <small class="form-text text-muted">Deja este campo vacío si no tienes un dato que agregar.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="guardarDatos()">Guardar</button>
      </div>
    </div>
  </div>
</div>
  <audio id="audioScaner" src="assets/sonido.mp3"></audio>

  <script>
    // Crea elemento
    const video = document.createElement("video");

    // Nuestro canvas
    const canvasElement = document.getElementById("qr-canvas");
    const canvas = canvasElement.getContext("2d", { willReadFrequently: true });

    // Div donde llegará nuestro canvas
    const btnScanQR = document.getElementById("btn-scan-qr");

    // Lectura desactivada
    let scanning = false;

    // Función para encender la cámara
    const encenderCamara = () => {
      navigator.mediaDevices
        .getUserMedia({ video: { facingMode: "environment" } })
        .then(function (stream) {
          scanning = true;
          btnScanQR.hidden = true;
          canvasElement.hidden = false;
          video.setAttribute("playsinline", true); 
          video.srcObject = stream;
          video.play();
          tick();
          scan();
        });
    };

    function tick() {
      canvasElement.height = video.videoHeight;
      canvasElement.width = video.videoWidth;
      canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

      scanning && requestAnimationFrame(tick);
    }

    function scan() {
      try {
        qrcode.decode();
      } catch (e) {
        setTimeout(scan, 300);
      }
    }

    const cerrarCamara = () => {
      video.srcObject.getTracks().forEach((track) => {
        track.stop();
      });
      canvasElement.hidden = true;
      btnScanQR.hidden = false;
    };

    const activarSonido = () => {
      var audio = document.getElementById('audioScaner');
      if (audio.readyState >= 2) { // 2 significa que el audio está cargado
        audio.play().catch(error => console.error("Error al reproducir el sonido:", error));
      }
    };

    const buscarEnBaseDeDatos = (codigoQR) => {
  console.log("Código escaneado:", codigoQR);
  $.ajax({
    url: "../db/buscar_vaca.php",
    type: "GET",
    data: { codigo: codigoQR },
    dataType: "json",
    success: function (registro) {
      console.log("Respuesta de la BD:", registro);
      if (registro && registro.id) {
        // Mostrar el modal
        $("#codigoVaca").val(registro.codigo); // Pasar el código de la vaca al modal
        $("#modalAgregarDatos").modal("show");
      } else {
        Swal.fire({
          title: "Código no encontrado",
          text: `El código ${codigoQR} no corresponde a ninguna vaca.`,
          icon: "error",
        });
      }
      activarSonido();
      cerrarCamara();
    },
    error: function (xhr, status, error) {
      console.error("Error en AJAX:", status, error);
      Swal.fire({
        title: "Error",
        text: "Hubo un error al buscar el código QR en la base de datos.",
        icon: "error",
      });
      cerrarCamara();
    },
  });
};


function guardarDatos() {
  // Obtén los valores de los campos
  const descendencia = document.getElementById("descendencia").value.trim();
  const promedioLeche = document.getElementById("promedioLeche").value.trim();
  const utilidad = document.getElementById("utilidad").value.trim();

  // Verifica que al menos un campo esté completado
  if (!descendencia && !promedioLeche && !utilidad) {
    Swal.fire({
      title: "Error",
      text: "Debes completar al menos uno de los campos: Descendencia, Promedio de Leche o Utilidad.",
      icon: "error",
    });
    return; // Detiene la ejecución si no hay datos
  }

  // Si al menos un campo está completado, procede a enviar los datos
  const formData = {
    codigo_vaca: document.getElementById("codigoVaca").value,
    descendencia: descendencia,
    promedio_leche: promedioLeche,
    utilidad: utilidad,
  };

  // Envía los datos al servidor usando AJAX
  $.ajax({
    url: "../db/guardar_promedio.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        Swal.fire({
          title: "Éxito",
          text: "Datos guardados correctamente.",
          icon: "success",
        });
        $("#modalAgregarDatos").modal("hide"); // Cierra el modal
      } else {
        Swal.fire({
          title: "Error",
          text: response.message || "Hubo un error al guardar los datos.",
          icon: "error",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en AJAX:", status, error);
      Swal.fire({
        title: "Error",
        text: "Hubo un error al guardar los datos.",
        icon: "error",
      });
    },
  });
}

    // Callback cuando termina de leer el código QR
    qrcode.callback = (respuesta) => {
      if (respuesta) {
        buscarEnBaseDeDatos(respuesta);
      }
    };

    // Evento para mostrar la cámara sin el botón
    window.addEventListener("load", () => {
      encenderCamara();
    });

    function buscarPorCodigo() {
  let codigo = document.getElementById("searchInput").value.trim();

  if (codigo.length < 1) {
    return;
  }

  $.ajax({
    url: "../db/buscar_vaca.php",
    type: "GET",
    data: { codigo: codigo },
    dataType: "json",
    success: function (registro) {
      if (registro && registro.id) {
        $("#codigoVaca").val(registro.codigo); // Pasar el código de la vaca al modal
        $("#modalAgregarDatos").modal("show");
      } else {
        Swal.fire({
          title: "Código no encontrado",
          text: `El código ${codigo} no corresponde a ninguna vaca.`,
          icon: "error",
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en AJAX:", status, error);
      Swal.fire({
        title: "Error",
        text: "Hubo un error al buscar el código en la base de datos.",
        icon: "error",
      });
    },
  });
}
  </script>
</body>
</html>

<?php require_once "vistas/parte_inferior.php" ?>