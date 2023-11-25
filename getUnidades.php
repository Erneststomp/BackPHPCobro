<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';
$out = [
    "result" => false,
    "msg" => "No entró"
];
// session_start();
// if (isset($_SESSION['user_nombre'])) {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        try {
            $nombreTabla = "unidades";

            // Consulta SQL para obtener todos los conceptos
            $sql = "SELECT * FROM $nombreTabla";
            $result = conectar_my_consulta($sql);

            $unidades = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $unidades[] = [
                        "id" => $row["id"],
                        "unidad" => $row["unidades"],
                        "estado" => $row["estado"]
                    ];
                }
                $out = [
                    "result" => true,
                    "unidades" => $unidades
                ];
            } else {
                $out = [
                    "result" => false,
                    "msg" => "No se encontraron unidades"
                ];
            }
        } catch (Exception $e) {
            // Registra el mensaje de error en un archivo de registro
            $error_message = "Error: " . $e->getMessage();
            error_log($error_message, 3, "errores.log");
            $out = [
                "result" => false,
                "msg" => $error_message
            ];
        }
    } else {
        // Si la solicitud no es un método GET, regresa un mensaje de error
        $out = [
            "result" => false,
            "msg" => "Solicitud no válida"
        ];
    }
// }
// else{
//     $out = [
//         "result" => false,
//         "msg" => "No se inicio sesion"
//     ];
// }

header('Content-Type: application/json');
echo json_encode($out);
?>
