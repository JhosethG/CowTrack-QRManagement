<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Obtener los datos enviados desde el formulario

$cedula = (isset($_POST['Cedula'])) ? $_POST['Cedula'] : '';
$username = (isset($_POST['username'])) ? $_POST['username'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';
$correo = (isset($_POST['correo'])) ? $_POST['correo'] : '';

// Verificar si algún campo está vacío
if (empty($cedula) || empty($username) || empty($password) || empty($correo)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
    exit();
}

// Encriptar la contraseña
$pass = md5($password);

// Verificar si el usuario ya existe
$consulta = "SELECT * FROM usuarios WHERE username = :username OR (cedula = :cedula)";
$resultado = $conexion->prepare($consulta);
$resultado->bindParam(':username', $username, PDO::PARAM_STR);

$resultado->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$resultado->execute();

if ($resultado->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "El usuario o la identificación ya existen."]);
    exit();
}

// Insertar los datos en la base de datos
$idRol = 2; // Valor fijo para idRol
$consulta = "INSERT INTO usuarios (cedula, username, password, correo, idRol) 
             VALUES (:cedula, :username, :password, :correo, :idRol)";
$resultado = $conexion->prepare($consulta);

$resultado->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$resultado->bindParam(':username', $username, PDO::PARAM_STR);
$resultado->bindParam(':password', $pass, PDO::PARAM_STR);
$resultado->bindParam(':correo', $correo, PDO::PARAM_STR);
$resultado->bindParam(':idRol', $idRol, PDO::PARAM_INT);

if ($resultado->execute()) {
    echo json_encode(["success" => true, "message" => "Registro exitoso."]);
} else {
    $errorInfo = $resultado->errorInfo();
    echo json_encode(["success" => false, "message" => "Error al registrar el usuario. Detalle: " . $errorInfo[2]]);
}

$conexion = null;
?>
