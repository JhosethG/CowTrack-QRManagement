<?php
require_once 'conexion.php'; // Asegúrate de que la ruta sea correcta

$id = $_GET["id"];

try {
    $conexion = Conexion::Conectar();

    $sql = "SELECT * FROM vacas WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $registro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($registro) {
        echo json_encode($registro);
    } else {
        echo json_encode(["success" => false, "message" => "Registro no encontrado."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}
?>
