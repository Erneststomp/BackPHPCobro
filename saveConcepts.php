<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';

$out = [
    "result" => false,
    "msg" => "No entró"
];



session_start();
// if (isset($_SESSION['user_nombre'])) {
    // Verificar que la solicitud sea de tipo POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener el cuerpo de la solicitud POST
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        if (!empty($data) && isset($data["servicio"]) && isset($data["costo"]) && isset($data["dependencia"]) && isset($data["unidad"]) && isset($data["plazos"])) {
            $servicio = $data["servicio"];
            $costo = $data["costo"];
            $dependencia = $data["dependencia"];
            $unidad = $data["unidad"];
            $subdependencia = $data["subdependencia"];
            if( $data["plazos"]){
                $plazos=1;
            }else{
                $plazos=0;
            }
            try {
                $nombreTabla1 = "conceptosDeCobro";

                // $UnidadName = str_replace(' ', '_', $unidad);
                // $nombreTabla2 = "cobrosRealizados".$UnidadName;
                // $estructuraTabla2 = definirEstructuraTablaCobros($nombreTabla2);
                // verificarCrearTabla(bd_name, $nombreTabla2, $estructuraTabla2);
                
                // Verificar si ya existe un concepto con el mismo nombre
                $sqlVerificarConcepto = "SELECT COUNT(*) AS count FROM $nombreTabla1 WHERE servicio = '$servicio' AND dependencia = '$dependencia'";
                $result = conectar_my_consulta($sqlVerificarConcepto);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if ($row["count"] > 0) {
                        $out = [
                            "result" => false,
                            "msg" => "El concepto que trata de agregar ya existe."
                        ];
                    } else {
                        // No se encontró un concepto con el mismo nombre, proceder con la inserción.
                        if($subdependencia === '') {
                            $sql2 = "INSERT INTO $nombreTabla1 (servicio, costo, dependencia, unidad, plazos, estado) "
                                  . "VALUES ('$servicio', $costo, '$dependencia', '$unidad', $plazos, 'active')";
                        } else {
                            $sql2 = "INSERT INTO $nombreTabla1 (servicio, costo, dependencia, subdependencia, unidad, plazos, estado) "
                                  . "VALUES ('$servicio', $costo, '$dependencia', '$subdependencia', '$unidad', $plazos, 'active')";
                        }
                        
                        // Asegúrate de usar consultas preparadas para evitar la inyección SQL
                        
                        conectar_my_consulta($sql2);

                        // // Agregar una columna a la tabla "cobrosRealizados" con el nombre del servicio
                        // $serviceName = str_replace(' ', '_', $servicio);
                        // $sql = "ALTER TABLE $nombreTabla2 ADD $serviceName VARCHAR(255)";
                        // conectar_my_consulta($sql);

                        $out = [
                            "result" => true,
                            "msg" => "Listo"
                        ];
                    }
                } else {
                    $out = [
                        "result" => false,
                        "msg" => "Error al verificar el concepto."
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
        }else {
            $out = [
                "result" => false,
                "msg" => "No se han enviado todos los valores."
            ];
        }
    } else {
        // La solicitud no es de tipo POST, regresa un mensaje de error
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
