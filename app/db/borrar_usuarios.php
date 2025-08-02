<?php
session_start();
include_once 'conexion.php';

// Recuperar el ID del usuario
$cedula = isset($_GET['id']) ? $_GET['id'] : null;

if ($cedula) {
    try {
        $objeto = new Conexion();
        $conexion = $objeto->conectar();

        // Eliminar usuario
        $query = "DELETE FROM usuarios WHERE cedula = :cedula";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            "success" => true,
            "message" => "Usuario eliminado exitosamente."
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error al eliminar el usuario: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se proporcionó un ID válido."
    ]);
}
?>
