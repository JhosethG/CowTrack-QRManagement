<?php
session_start();

if (!isset($_SESSION["s_username"])) {
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit();
}

// Incluye el archivo de conexión
require_once "conexion.php"; // Asegúrate de que la ruta sea correcta

// Obtén la conexión usando la clase Conexion
$conexion = Conexion::Conectar();

// Verifica si la conexión está definida
if (!$conexion) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit();
}

// Recupera los datos del formulario
$codigo_vaca = $_POST["codigo_vaca"];
$descendencia = $_POST["descendencia"];
$promedio_leche = $_POST["promedio_leche"];
$utilidad = $_POST["utilidad"];
$fecha = date("Y-m-d"); // Fecha actual

// Prepara la consulta SQL
$sql = "INSERT INTO promedio (codigo_vaca, promedio_leche, descendencia_real, utilidad, fecha) 
        VALUES (:codigo_vaca, :promedio_leche, :descendencia, :utilidad, :fecha)";
$stmt = $conexion->prepare($sql);

// Asocia los parámetros
$stmt->bindParam(":codigo_vaca", $codigo_vaca, PDO::PARAM_STR);
$stmt->bindParam(":promedio_leche", $promedio_leche, PDO::PARAM_STR);
$stmt->bindParam(":descendencia", $descendencia, PDO::PARAM_INT);
$stmt->bindParam(":utilidad", $utilidad, PDO::PARAM_STR);
$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);

// Ejecuta la consulta
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar los datos"]);
}

// Cierra la conexión
$stmt = null;
$conexion = null;
?>