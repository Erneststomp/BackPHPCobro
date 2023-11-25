<?php
require 'funciones.php';
require_once 'config.php';
$out = [
    "result" => false,
    "msg" => "No se inicio sesion",
    "active"=>"el usuario esta activo"
];
// Obtener el cuerpo de la solicitud POST
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($data) && isset($data["email"]) && isset($data["password_"])) {
        $email = $data["email"];
        $password_ = $data["password_"];
        $password_e = base64_encode($password_);
        try {
            $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password_ = '$password_e'";
            $result = conectar_my_consulta($sql);
            
            if ($result && $result->num_rows > 0) {
                // Las credenciales son válidas, inicia la sesión
                
                    session_start();
                    $row = $result->fetch_assoc();
                if($row['estado']=='active'){
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_nombre'] = $row['nombre'];
                    $_SESSION['user_unidad'] = $row['unidad'];
                    $_SESSION['user_typeOfUser'] = $row['typeOfUser'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['user_dependencia'] = $row['dependencia'];
                    $out = [
                        "result" => true,
                        "msg" => "Sesion iniciada correctamente",
                        "user" => [
                            "nombre" => $row['nombre'],
                            "typeOfUser" => $row['typeOfUser'],
                            "unidad"=>$row['unidad'],
                            "email"=>$row['email'],
                            "dependencia"=>$row['dependencia']
                        ]
                    ];
                }else{
                    session_destroy();
                    $out = [
                        "result" => false,
                        "msg" => "El usuario ha sido deshabilitado",
                    ];
                }
            }
            else {
                // Las credenciales no son válidas
                $out = [
                    "result" => false,
                    "msg" => "credenciales incorrectas"
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
    }
} else {
    // Si la solicitud no es un método GET, regresa un mensaje de error
    $out = [
        "result" => false,
        "msg" => "Solicitud no valida"
    ];
}

header('Content-Type: application/json');
echo json_encode($out);
?>
