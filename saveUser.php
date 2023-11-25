<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';
$out = [
    "result" => false,
    "msg" => "No entró"
];

$nombreTabla = "usuarios";
$sqlVerificarexitencia = "SELECT * FROM $nombreTabla";
$result1 = conectar_my_consulta($sqlVerificarexitencia);
$usuarioInicioSesion=false;
session_start();
if(isset($_SESSION['user_nombre']) && isset($_SESSION['user_typeOfUser'])){
    if($_SESSION["user_typeOfUser"]=="Admin"){
        $usuarioInicioSesion=true; 
    }else{
        $out = [
            "result" => false,
            "msg" => "El usuario no tiene los permisos adecuados, contacte a un adminsitrador"
        ];
    }
}else{
    $out = [
        "result" => false,
        "msg" => "No se inicio sesion"
    ];
}

if ($usuarioInicioSesion==true || $result1->num_rows == 0) {
    // Verificar que la solicitud sea de tipo POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener el cuerpo de la solicitud POST
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        if (!empty($data) && isset($data["nombre"]) && isset($data["email"]) && isset($data["password_"]) && isset($data["unidad"]) && isset($data["typeOfUser"]) &&  isset($data["dependencia"])) {
            $nombre = $data["nombre"];
            $email = $data["email"];
            $password_ = $data["password_"];
            $unidad = $data["unidad"];
            $dependencia = $data["dependencia"];
            $password_e = base64_encode($password_);
            if($result1->num_rows == 0){
                $typeOfUser = "Admin";
            }else{
                $typeOfUser = $data["typeOfUser"];
            }
            

            try {
                $sqlVerificarUsuario = "SELECT COUNT(*) AS count FROM $nombreTabla WHERE nombre = '$nombre' AND email = '$email'";
                $result = conectar_my_consulta($sqlVerificarUsuario);
 
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if ($row["count"] > 0) {
                        $out = [
                            "result" => false,
                            "msg" => "Ya existe un usuario con los mismos datos de registro"
                        ];
                    } else {
                        // No se encontró un usuario con los mismos datos, proceder con la inserción.
                        $sql2 = "INSERT INTO $nombreTabla (nombre, email, password_, dependencia, typeOfUser, unidad,estado)"
                            . "VALUES ('$nombre', '$email', '$password_e', '$dependencia', '$typeOfUser', '$unidad','active')";
                        conectar_my_consulta($sql2);
                        $out = [
                            "result" => true,
                            "msg" => "Listo"
                        ];
                    }
                };
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
        // La solicitud no es de tipo POST, regresa un mensaje de error
        $out = [
            "result" => false,
            "msg" => "Solicitud no válida"
        ];
    }
}
header('Content-Type: application/json');
echo json_encode($out);
?>
