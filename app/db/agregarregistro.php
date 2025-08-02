<?php
// Incluir la clase Conexion
require_once 'conexion.php'; // Asegúrate de que la ruta sea correcta

// Obtener los datos del formulario enviados por AJAX
$raza = $_POST["raza"];
$utilidad = $_POST["utilidad"];
$litros_por_vaca = $_POST["litros_por_vaca"];
$descendencia = $_POST["descendencia"];
$codigo = uniqid(); // Generar un código único automáticamente

try {
    // Conectar a la base de datos usando la clase Conexion
    $conexion = Conexion::Conectar();

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO vacas (codigo, raza, utilidad, litros_por_vaca, descendencia) 
            VALUES (:codigo, :raza, :utilidad, :litros_por_vaca, :descendencia)";

    $stmt = $conexion->prepare($sql);

    // Vincular los parámetros
    $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $stmt->bindParam(':raza', $raza, PDO::PARAM_STR);
    $stmt->bindParam(':utilidad', $utilidad, PDO::PARAM_STR);
    $stmt->bindParam(':litros_por_vaca', $litros_por_vaca, PDO::PARAM_STR);
    $stmt->bindParam(':descendencia', $descendencia, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registro guardado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar el registro."]);
    }
} catch (PDOException $e) {
    // Manejar errores de la base de datos
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}
?>