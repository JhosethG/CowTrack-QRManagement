<?php
require_once 'conexion.php'; // Asegúrate de que la ruta sea correcta

$id = $_POST["id"];

try {
    $conexion = Conexion::Conectar();

    $sql = "DELETE FROM vacas WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registro eliminado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar el registro."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}
?>
