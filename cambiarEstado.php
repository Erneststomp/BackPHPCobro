<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';
$out = [
    "result" => false,
    "msg" => "Acción no permitida",
    'data'=>''
];

session_start();
// Verificar que el usuario haya iniciado sesión y tenga permisos de administrador.
if (isset($_SESSION['user_nombre']) && isset($_SESSION['user_typeOfUser']) && $_SESSION['user_typeOfUser'] == 'Admin') {
    // Verificar que la solicitud sea de tipo POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener los datos enviados en la solicitud
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $out["data"] =$data;
        // Validar que se reciban los datos necesarios
        if (!empty($data) && isset($data["tabla"]) && isset($data["id"]) && isset($data["estado"])) {
            $tabla = $data["tabla"];
            $id = $data["id"];
            $nuevoEstado = $data["estado"];
            $notas=$data["notas"];
            // Crear la consulta SQL para actualizar el estado
            $sql = "UPDATE $tabla SET estado = '$nuevoEstado', notas = '$notas' WHERE id = $id";
            // Ejecutar la consulta
            $resultado = conectar_my_consulta($sql);

            if ($resultado) {
                $out = [
                    "result" => true,
                    "msg" => "Estado actualizado correctamente"
                ];
            } else {
                $out = [
                    "result" => false,
                    "msg" => "Error al actualizar el estado"
                ];
            }
        } else {
            $out["msg"] = "Datos incompletos";
        }
    }
} else {
    $out["msg"] = "Usuario no autorizado o no ha iniciado sesión";
}

header('Content-Type: application/json');
echo json_encode($out);
?>
