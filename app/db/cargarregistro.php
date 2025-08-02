<?php
// Incluir la clase Conexion
require_once 'conexion.php'; // Asegúrate de que la ruta sea correcta

try {
    // Conectar a la base de datos usando la clase Conexion
    $conexion = Conexion::Conectar();

    // Preparar la consulta SQL para obtener los registros
    $sql = "SELECT * FROM vacas";
    $stmt = $conexion->prepare($sql);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados como un array asociativo
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los registros en formato JSON
    echo json_encode($registros);
} catch (PDOException $e) {
    // Manejar errores de la base de datos
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}
?>