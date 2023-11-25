<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';

// Inicializar el arreglo de respuesta
$out = [
    "result" => false,
    "msg" => "No entró"
];

session_start();

if (isset($_SESSION['user_nombre'])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener el cuerpo de la solicitud POST
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        if (!empty($data) && isset($data["cliente"]) && isset($data["numero_contacto"]) && isset($data["total"]) && isset($data["abono"]) && isset($data["adeudo"]) && isset($data["concepto"])) {
            $unidad = $_SESSION['user_unidad'];
            $dependencia = $_SESSION['user_dependencia'];
            $user = $_SESSION['user_nombre'];
            $cliente = $data["cliente"];
            $numero_contacto = $data["numero_contacto"];
            $total = $data["total"];
            $abono = $data["abono"];
            $conceptos = $data['concepto'];
            if($abono!==0){
                $pagado=0;
            }else{
                $pagado=1;
            }
            if ($abono != 0) {
                $adeudo = $total - $abono;
            } else {
                $adeudo = 0;
            }
            $ultimo_folio = "000000";
            $letra = "A";
            $nuevo_folio = "";
            $nombreTabla = 'folios';
            $sql1 = "SELECT * FROM $nombreTabla WHERE unidades = '$unidad' AND (dependencia = '$dependencia' OR dependencia = 'General' )";
            $resultado = conectar_my_consulta($sql1);
            
            if ($resultado && $resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                if ($row) {
                    $folioid= $row["id"];
                    $letra = $row["letra"];
                    $ultimo_folio = $row["ultimo_folio"];
                }
            }
            $datapdf=[];
            $folio = str_pad((intval($ultimo_folio) + 1), 6, '0', STR_PAD_LEFT);
            $nuevo_folio = $folio.$letra;
            date_default_timezone_set('America/Mexico_City');
            $fechaActual = date('Y-m-d H:i:s');

            $datapdf[] = [
                "unidad"=>$unidad,
                "dependencia" => $dependencia,
                "usuario"=>$user,
                "cliente"=>$cliente,
                "conceptos"=>$conceptos,     
                "total"=>$total,
                "abono"=>$abono,
                "adeudo"=>$adeudo,
                "folio"=>$nuevo_folio,
                "fecha"=>$fechaActual,
            ];
            
            try {
                $nombreTabla = "registroVentas";
                $sql2 = "INSERT INTO $nombreTabla (unidad, dependencia, user, cliente, numero_contacto,conceptos, total, abono, adeudo,fecha,estado,pagado,folio)"
                    . "VALUES ('$unidad', '$dependencia', '$user', '$cliente', $numero_contacto,'$conceptos', $total, $abono, $adeudo,'$fechaActual','active','$pagado','$nuevo_folio')";
                conectar_my_consulta($sql2);
                $nuevoUltimoFolio=$ultimo_folio+1;
                $sqlUpdate = "UPDATE folios SET ultimo_folio = '$nuevoUltimoFolio' WHERE id = '$folioid'";
                conectar_my_consulta($sqlUpdate);
                $out = [
                    "result" => true,
                    "msg" => "Listo",
                    "data"=> $datapdf
                ];
            } catch (Exception $e) {
                // Registra el mensaje de error en un archivo de registro
                $error_message = "Error: " . $e->getMessage();
                error_log($error_message, 3, "errores.log");
                $out = [
                    "result" => false,
                    "msg" => $error_message
                ];
            }
        }
    } else {
        // Si la solicitud no es un método POST, regresa un mensaje de error
        $out = [
            "result" => false,
            "msg" => "Solicitud no válida"
        ];
    }
} else {
    $out = [
        "result" => false,
        "msg" => "No se inició sesión"
    ];
}

header('Content-Type: application/json');
echo json_encode($out);
?>
