<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="assets/sweetalert/sweetalert2.min.css">
</head>
<body class="bg-success d-flex justify-content-center align-items-center vh-100">

<div>
    <div class="bg-white p-5 rounded-5 text-muted" style="width: 25rem">
        <div class="d-flex justify-content-center" style="font-size: 7rem"><i class="fa-solid fa-circle-user"></i></div>

        <form id="formRecovery" class="form" action="" method="post">
            <div class="text-center fs-2 fw-bold">Recuperar Contraseña</div>

            <!-- Campo Identificación -->
            <div class="input-group mt-3">
                <i class="fa-solid fa-user input-group-text bg-success text-white"></i>
                <input class="form-control" type="text" placeholder="Identificación" name="identificacion" id="identificacion" required>
            </div>

            <!-- Botón para enviar código -->
            <div>
                <button type="button" id="sendCode" class="btn btn-success text-white w-100 mt-3">Enviar Código</button>
            </div>

            <!-- Campo Código de Validación -->
            <div class="input-group mt-3" id="codeSection" style="display: none;">
                <i class="fa-solid fa-code input-group-text bg-success text-white"></i>
                <input class="form-control" type="text" placeholder="Código de Validación" name="codigo" id="codigo">
            </div>

            <!-- Campo Nueva Contraseña -->
            <div class="input-group mt-3" id="passwordSection" style="display: none;">
                <i class="fa-solid fa-key input-group-text bg-success text-white"></i>
                <input class="form-control" type="password" placeholder="Nueva Contraseña" name="password" id="password">
            </div>

            <!-- Botón Confirmar -->
            <div>
                <button type="submit" id="confirmBtn" class="btn btn-success text-white w-100 mt-3" disabled>Confirmar</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/sweetalert/sweetalert2.all.min.js"></script>
<script src="codigo_validacion.js"></script>

</body>
</html>