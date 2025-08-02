<?php
require_once 'conexion.php'; // Asegúrate de incluir la conexión a la base de datos

$filtroFecha = $_GET["fecha"] ?? "";
$filtroVaca = $_GET["codigo_vaca"] ?? "";

try {
    $conexion = Conexion::Conectar();
    $sql = "SELECT * FROM promedio WHERE 1=1";

    if (!empty($filtroFecha)) {
        $sql .= " AND fecha = :fecha";
    }
    if (!empty($filtroVaca)) {
        $sql .= " AND codigo_vaca = :codigo_vaca";
    }

    $stmt = $conexion->prepare($sql);

    if (!empty($filtroFecha)) {
        $stmt->bindParam(':fecha', $filtroFecha);
    }
    if (!empty($filtroVaca)) {
        $stmt->bindParam(':codigo_vaca', $filtroVaca);
    }

    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($registros);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>