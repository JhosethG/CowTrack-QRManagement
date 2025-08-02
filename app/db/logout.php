<?php
session_start(); // Inicia la sesión para manipularla
// Elimina las variables de sesión
unset($_SESSION["s_username"]);
unset($_SESSION["s_idRol"]);
unset($_SESSION["s_rol_descripcion"]);

// Destruye la sesión completamente
session_destroy();

// Redirige al login o página de inicio
header("Location: ../../index.php");
exit;
?>
