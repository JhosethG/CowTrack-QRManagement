<?php
require_once 'conexion.php'; // Asegúrate de incluir la conexión a la base de datos

try {
    $conexion = Conexion::Conectar();
    $sql = "SELECT DISTINCT codigo_vaca FROM promedio";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $vacas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($vacas);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>