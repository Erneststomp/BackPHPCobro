<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';
$out = [
    "result" => false,
    "msg" => "No entró"
];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener el cuerpo de la solicitud POST
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);
        try { 
            
            $sql = "SELECT * FROM $nombreTabla";
            $result = conectar_my_consulta($sql);
            $conceptos = [];
    
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $conceptos[] = [
                        "id" => $row["id"],
                        "servicio" => $row["servicio"],
                        "costo" => $row["costo"],
                        "area" => $row["area"],
                        "unidad" => $row["unidad"],
                        "plazos" => $row["plazos"]
                    ];
                }
                $nombreTabla='providedServices';
                $estructuraTabla = definirEstructuraTablaConceptos($nombreTabla);
                verificarCrearTabla(bd_name, $nombreTabla, $estructuraTabla);
                $out = [
                    "result" => true,
                    "conceptos" => $conceptos
                ];
            }
          
            $out = [
                "result" => true,
                "msg" => "Listo"
            ];
            // Mensaje de depuración
            echo "Operación exitosa.";
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
header('Content-Type: application/json');
echo json_encode($out);
?>
