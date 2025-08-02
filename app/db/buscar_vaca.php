<?php
// Incluir el archivo de conexión
include('conexion.php');  // Asegúrate de que la ruta sea correcta

// Obtener la conexión usando la clase Conexion
$pdo = Conexion::Conectar();

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Preparamos y ejecutamos la consulta para buscar el código en la base de datos
    $query = "SELECT * FROM vacas WHERE codigo = :codigo LIMIT 1";
    $stmt = $pdo->prepare($query);  // Ahora usamos la conexión correcta
    $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);

    // Ejecutamos la consulta
    $stmt->execute();

    // Comprobamos si se encontró la vaca
    if ($stmt->rowCount() > 0) {
        $vaca = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($vaca);  // Devolvemos la vaca como JSON
    } else {
        // Si no se encontró, devolvemos un error
        echo json_encode(['error' => 'Código no encontrado']);
    }
} else {
    echo json_encode(['error' => 'No se ha enviado el código']);
}
?>
