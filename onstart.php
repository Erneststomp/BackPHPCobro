<?php
require 'funciones.php';
require_once 'config.php';
require 'estructuras.php';

$responses = []; // Array para almacenar las respuestas

verificarCrearBaseDeDatos(bd_name);

$nombreTabla = '';
// Definir una lista de nombres de tablas y sus estructuras
$tablas = [
    ["nombre" => "dependencias", "estructura" => definirEstructuraTablaDependencias($nombreTabla)],
    ["nombre" => "unidades", "estructura" => definirEstructuraTablaUnidades($nombreTabla)],
    ["nombre" => "usuarios", "estructura" => definirEstructuraTablaUsers($nombreTabla)],
    ["nombre" => "conceptosdecobro", "estructura" => definirEstructuraTablaConceptos($nombreTabla)],
    ["nombre" => "registroVentas", "estructura" => definirEstructuraTablaVentas($nombreTabla)],
    ["nombre" => "folios", "estructura" => definirEstructuraTablaFolios($nombreTabla)],
    ["nombre" => "subdependencias", "estructura" => definirEstructuraTablaSubdependencias($nombreTabla)]
];

// Recorre la lista de tablas y verifica/crea cada una
foreach ($tablas as $tabla) {
    $nombreTabla = $tabla["nombre"];
    $estructuraTabla = $tabla["estructura"];
    // Almacena las respuestas en el array
    $responses[] = verificarCrearTabla(bd_name, $nombreTabla, $estructuraTabla);
}

// Envía todas las respuestas en formato JSON
header('Content-Type: application/json');
echo json_encode($responses);
?>
