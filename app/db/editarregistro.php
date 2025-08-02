<?php
require_once 'conexion.php'; // Asegúrate de que la ruta sea correcta

$id = $_POST["id"];
$raza = $_POST["raza"];
$utilidad = $_POST["utilidad"];
$litros_por_vaca = $_POST["litros_por_vaca"];
$descendencia = $_POST["descendencia"];

try {
    $conexion = Conexion::Conectar();

    $sql = "UPDATE vacas SET raza = :raza, utilidad = :utilidad, litros_por_vaca = :litros_por_vaca, descendencia = :descendencia WHERE id = :id";
    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':raza', $raza, PDO::PARAM_STR);
    $stmt->bindParam(':utilidad', $utilidad, PDO::PARAM_STR);
    $stmt->bindParam(':litros_por_vaca', $litros_por_vaca, PDO::PARAM_STR);
    $stmt->bindParam(':descendencia', $descendencia, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registro actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar el registro."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión: " . $e->getMessage()]);
}
?>
