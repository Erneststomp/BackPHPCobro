<?php

// Si estás usando sesiones en PHP, inicia la sesión
session_start();

// Configurar cabeceras para CORS y aceptar cookies - ajustar según sea necesario para tu configuración específica
header('Access-Control-Allow-Origin: http://localhost:3000'); // Ajusta al dominio de tu cliente
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Verificar la sesión actual
if (isset($_SESSION['user']) && $_SESSION['isLoggedIn']) {
    // Suponiendo que guardas los datos del usuario en la sesión
    $response = array(
        'isLoggedIn' => true,
        'user' => $_SESSION['user'] // Información del usuario
    );
} else {
    // No hay sesión válida
    $response = array(
        'isLoggedIn' => false
    );
}

// Devolver la respuesta como JSON
echo json_encode($response);

?>
