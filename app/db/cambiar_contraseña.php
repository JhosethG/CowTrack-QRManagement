<?php
session_start();
include_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Obtener los datos enviados desde el formulario
$cedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($cedula) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Cedula y nueva contraseña son obligatorios."]);
    exit();
}

// Encriptar la nueva contraseña
$pass = md5($password);

// Actualizar la contraseña en la base de datos
$consulta = "UPDATE usuarios SET password = :password WHERE cedula = :cedula";
$resultado = $conexion->prepare($consulta);
$resultado->bindParam(':cedula', $cedula, PDO::PARAM_STR);
$resultado->bindParam(':password', $pass, PDO::PARAM_STR);

if ($resultado->execute()) {
    echo json_encode(["success" => true, "message" => "La contraseña ha sido cambiada correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al cambiar la contraseña."]);
}

$conexion = null;
?>
