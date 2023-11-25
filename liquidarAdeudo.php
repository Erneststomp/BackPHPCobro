<?php
require 'funciones.php';
require_once 'config.php';
$out = [
    "result" => false,
    "msg" => "No se inicio sesion",
];
// Obtener el cuerpo de la solicitud POST
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
    if (!empty($data) && isset($data["folio"])) {
        $folio = $data["folio"];
        try {
            // Primero, seleccionar el registro para obtener el valor 'total'
            $sqlSelect = "SELECT * FROM registroVentas WHERE folio = '$folio'";
            $result = conectar_my_consulta($sqlSelect);
            
            $ventas = [];
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $out= ["msg"=>$row['folio']];
                        $total = $row["total"];
                        $abono = $row["abono"];
                        $adeudo = $row["adeudo"];
                        $pagado=$row["pagado"];
                        $folio=$row['folio'];
                }
                $out = [
                    "result" => true,
                    "ventas" => $ventas
                ];
            }
            $row = mysqli_fetch_assoc($result);
                // Ahora, actualizar el registro
                $sqlUpdate = "UPDATE registroVentas SET pagado = 1, abono = '$total', adeudo = 0, liquidacion=NOW() WHERE folio = '$folio'";
                conectar_my_consulta($sqlUpdate);
                $out = [
                    "result" => true,
                    "msg" => "Registro actualizado correctamente"
                ];
        } catch (Exception $e) {
            $error_message = "Error: " . $e->getMessage();
            error_log($error_message, 3, "errores.log");
            $out = [
                "result" => false,
                "msg" => $error_message
            ];
        }
    }
} else {
    $out = [
        "result" => false,
        "msg" => "Solicitud no valida"
    ];
}

header('Content-Type: application/json');
echo json_encode($out);
?>
