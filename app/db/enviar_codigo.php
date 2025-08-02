<?php
include_once 'conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();

$cedula = $_POST['cedula'] ?? '';
$codigo = $_POST['codigo'] ?? '';

if (empty($cedula) || empty($codigo)) {
    echo json_encode(["success" => false, "message" => "Datos incompletos."]);
    exit();
}

$consulta = "SELECT correo FROM usuarios WHERE cedula = :cedula";
$resultado = $conexion->prepare($consulta);

$resultado->bindParam(':cedula', $cedula);
$resultado->execute();

if ($resultado->rowCount() === 0) {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
    exit();
}

$usuario = $resultado->fetch(PDO::FETCH_ASSOC);
$correo = $usuario['correo'];

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'codigoderecuperacion2023@gmail.com';
    $mail->Password = 'qvsr rqni qsep hngz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('codigoderecuperacion2023@gmail.com', 'Recuperación de Contraseña');
    $mail->addAddress($correo);

    $mail->isHTML(true);
    $mail->Subject = 'Código de Recuperación';
    $mail->Body = "Tu código de recuperación es: $codigo";

    $mail->send();
    echo json_encode(["success" => true, "message" => "Correo enviado."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error al enviar el correo."]);
}