<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';
$out = [
    "result" => false,
    "msg" => "No entró"
];
 session_start();
if (isset($_SESSION['user_nombre'])) {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        try {
            $nombreTabla = "conceptosDeCobro";
            // Consulta SQL para obtener todos los conceps
            if($_SESSION['user_typeOfUser']==="Admin"){
                $sql = "SELECT * FROM $nombreTabla";
            }else{
                $sql = "SELECT * FROM " . $nombreTabla . " WHERE unidad='" . $_SESSION['user_unidad'] . "' AND dependencia='" . $_SESSION['user_dependencia'] . "'";
            }
            
            $result = conectar_my_consulta($sql);

            $conceptos = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $conceptos[] = [
                        "id" => $row["id"],
                        "servicio" => $row["servicio"],
                        "costo" => $row["costo"],
                        "unidad" => $row["unidad"],
                        "plazos" => $row["plazos"],
                        "dependencia"=>$row['dependencia'],
                        "estado" => $row["estado"],
                    ];
                }
                $out = [
                    "result" => true,
                    "conceptos" => $conceptos
                ];
            } else {
                $out = [
                    "result" => false,
                    "msg" => "No se encontraron conceptos"
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
}
else{
    $out = [
        "result" => false,
        "msg" => "No se inicio sesion"
    ];
}

header('Content-Type: application/json');
echo json_encode($out);
?>
