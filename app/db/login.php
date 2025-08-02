<?php
session_start();
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->conectar();

// Recepción de los datos enviados mediante POST desde el JS
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

// Encriptamos la contraseña usando MD5
$pass = md5($password);

// Depuración: Imprimir los valores recibidos
error_log("Username: " . $username);
error_log("Password: " . $password);

// Consulta SQL con parámetros enlazados para mayor seguridad
$consulta = "SELECT usuarios.username AS username, usuarios.cedula AS cedula, usuarios.idRol AS idRol, 
                    roles.nombre AS rol
             FROM usuarios 
             JOIN roles ON usuarios.idRol = roles.id 
             WHERE usuarios.username = :username AND usuarios.password = :password";

// Depuración: Imprimir la consulta SQL antes de ejecutarla
error_log("Consulta SQL: " . $consulta);

// Preparar la consulta
$resultado = $conexion->prepare($consulta);

// Enlazar los parámetros de forma segura
$resultado->bindParam(':username', $username, PDO::PARAM_STR);
$resultado->bindParam(':password', $pass, PDO::PARAM_STR);

// Ejecutar la consulta
$resultado->execute();

if ($resultado->rowCount() >= 1) {
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    
    // Guardamos los datos relevantes en la sesión
    $_SESSION["s_username"] = $data[0]["username"];  // Guarda el username
    $_SESSION["s_cedula"] = $data[0]["cedula"];  // Guarda la cédula
    $_SESSION["s_idRol"] = $data[0]["idRol"];  // Guarda el id del rol

    // Respondemos con éxito
    $response = [
        'success' => true,
        'idRol' => $data[0]["idRol"],  // Incluye el idRol en la respuesta JSON
        'rol' => $data[0]["rol"],  // Incluye el nombre del rol en la respuesta JSON
    ];
} else {
    $_SESSION["s_username"] = null;  // Si las credenciales no son correctas, limpiamos la sesión
    $_SESSION["s_cedula"] = null;
    $response = [
        'success' => false,
        'message' => 'Usuario y/o contraseña incorrectos'
    ];
}

// Depuración: Imprimir el resultado de la consulta
error_log("Resultado consulta: " . json_encode($response));

// Enviar la respuesta JSON al frontend
echo json_encode($response);

// Cerrar la conexión
$conexion = null;
?>
