<?php
require_once 'config.php';

// Función para conectar a la base de datos y ejecutar una consulta SQL.
function conectar_my_consulta($consultaSQL){
    $mysqli_obj = new mysqli(bd_host, bd_user, bd_password, bd_name, bd_port);
    $mysqli_obj->set_charset("utf8");
    $my_result = $mysqli_obj->query($consultaSQL);
    $mysqli_obj->close();
    return $my_result;
}

function verificarCrearBaseDeDatos($nombreBaseDatos) {
    // Conéctate al servidor de base de datos.
    $conexion = new mysqli(bd_host, bd_user, bd_password);
    
    // Verifica la conexión.
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    // Consulta SQL para verificar si la base de datos existe.
    $sql = "SHOW DATABASES LIKE '$nombreBaseDatos'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows == 0) {
        // La base de datos no existe, entonces la creamos.
        $sqlCrearBaseDatos = "CREATE DATABASE $nombreBaseDatos";
        if ($conexion->query($sqlCrearBaseDatos) === TRUE) {
            $response = [
                "result" => true,
                "msg" => "Base de datos creada con exito."
            ];
        } else {
            $response = [
                "result" => false,
                "msg" => "Error al crear la base de datos: " . $conexion->error
            ];
        }
    } else {
        $response = [
            "result" => true,
            "msg" => "La base de datos ya existe."
        ];
    }

    // Cierra la conexión a la base de datos.
    $conexion->close();

    return json_encode($response);
}

function verificarCrearTabla($nombreBaseDatos, $tablaNombre, $estructuraTabla) {
    // Conéctate al servidor de base de datos.
    $conexion = new mysqli(bd_host, bd_user, bd_password);

    // Verifica la conexión.
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    // Cambia el conjunto de caracteres y selecciona la base de datos.
    $conexion->set_charset("utf8");
    $conexion->select_db($nombreBaseDatos);

    // Consulta SQL para verificar si la tabla existe.
    $sql = "SHOW TABLES LIKE '$tablaNombre'";
    $resultado = $conexion->query($sql);
    
    if ($resultado->num_rows == 0) {
        // La tabla no existe, así que la creamos.
        $sqlCrearTabla = "CREATE TABLE $tablaNombre (";

        // Agrega las columnas a la estructura de la tabla.
        foreach ($estructuraTabla as $columna => $tipo) {
            $sqlCrearTabla .= "$columna $tipo, ";
        }

        // Elimina la coma y el espacio al final.
        $sqlCrearTabla = rtrim($sqlCrearTabla, ", ");

        $sqlCrearTabla .= ")";
        
        if ($conexion->query($sqlCrearTabla) === TRUE) {
            $response = [
                "result" => true,
                "msg" => "Tabla $tablaNombre creada con exito."
            ];
        } else {
            $response = [
                "result" => false,
                "msg" => "Error al crear la tabla $tablaNombre: " . $conexion->error
            ];
        }
    } else {
        $response = [
            "result" => true,
            "msg" => "La tabla $tablaNombre ya existe."
        ];
    }

    // Cierra la conexión a la base de datos.
    $conexion->close();

    return json_encode($response);
}
?>
