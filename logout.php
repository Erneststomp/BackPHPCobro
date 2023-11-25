<?php
require_once "config.php";

// Iniciar la sesión
session_start();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión o a donde desees
// header('Location: login.php');

$out = [
    "result" => true,
    "msg" => "Sesión cerrada correctamente"
];

// Envía la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($out);
exit;
?>
