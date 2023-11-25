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
            $nombreTabla = "registroVentas"; 
            if($_SESSION['user_typeOfUser']==="Admin"){
                $sql = "SELECT * FROM $nombreTabla";
            }else{
                $sql = "SELECT * FROM " . $nombreTabla . " WHERE unidad='" . $_SESSION['user_unidad'] . "' AND dependencia='" . $_SESSION['user_dependencia'] . "'";
            }
            $result = conectar_my_consulta($sql);

            $ventas = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $ventas[] = [
                        "id" => $row["id"],
                        "unidad" => $row["unidad"],
                        "dependencia" => $row["dependencia"],
                        "user" => $row["user"],
                        "cliente" => $row["cliente"],
                        "numero_contacto" => $row["numero_contacto"],
                        "conceptos" => $row["conceptos"],
                        "total" => $row["total"],
                        "abono" => $row["abono"],
                        "adeudo" => $row["adeudo"],
                        "fecha" => $row["fecha"],
                        "estado" => $row["estado"],
                        "pagado"=>$row["pagado"],
                        "folio"=>$row['folio'],
                        "notas"=>$row['notas'],
                        "liquidacion"=>$row['liquidacion']
                    ];
                }
                $out = [
                    "result" => true,
                    "ventas" => $ventas
                ];
            } else {
                $out = [
                    "result" => true,
                    "msg" => "No se encontraron ventas"
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
