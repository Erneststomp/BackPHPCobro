<?php

function definirEstructuraTablaConceptos($nombreTabla) {
    $estructuraTabla = [
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "servicio" => "VARCHAR(255)",    
        "unidad" => "VARCHAR(255)",    
        "dependencia" => "VARCHAR(255)",
        "subdependencia"=>"VARCHAR(255)",
        "costo" => "INT",
        "plazos" => "TINYINT(1)",
        "estado"=>'VARCHAR(255)',
        "notas"=>"VARCHAR(255)"
    ];
    return $estructuraTabla;
}

function definirEstructuraTablaVentas($nombreTabla) {
    $estructuraTabla = [
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "unidad" => "VARCHAR(255)",
        "dependencia" => "VARCHAR(255)",
        "subdependencia" => "VARCHAR(255)",
        "user" => "VARCHAR(255)",
        "cliente" => "VARCHAR(255)",
        "numero_contacto" => "INT",
        "total" => "INT",
        "abono" => "INT",
        "adeudo" => "INT",
        "conceptos" => "VARCHAR(255)",
        "fecha"=>'DATETIME',
        "estado"=>'VARCHAR(255)',
        "pagado"=>"INT",
        "notas"=>"VARCHAR(255)",
        "folio"=>"VARCHAR(255)",
        "liquidacion"=>"VARCHAR(255)"
    ];
    return $estructuraTabla;
}

function definirEstructuraTablaCobros($nombreTabla) {
    $estructuraTabla = [
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "Fecha"=>'DATETIME',
        "usuarios" => "VARCHAR(255)",
        "estado"=>'VARCHAR(255)'
    ];
    return $estructuraTabla;
}

function definirEstructuraTablaUsers($nombreTabla){

    $estructuraTabla = [
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "nombre" => "VARCHAR(255)",
        "email" => "VARCHAR(255)",
        "password_" => "VARCHAR(255)",
        "dependencia" => "VARCHAR(255)",
        "typeOfUser" => "VARCHAR(255)",
        "unidad"=>"VARCHAR(255)",
        "estado"=>'VARCHAR(255)',
        "notas"=>"VARCHAR(255)"
    ];
    return $estructuraTabla;
}

function definirEstructuraTablaDependencias($nombreTabla){
    $estructuraTabla=[
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "dependencias"=>"VARCHAR(255)",
        "estado"=>'VARCHAR(255)',
        "notas"=>"VARCHAR(255)"
    ];
    return $estructuraTabla;
}
function definirEstructuraTablaUnidades($nombreTabla){
    $estructuraTabla=[
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "unidades"=>"VARCHAR(255)",
        "estado"=>'VARCHAR(255)',
        "notas"=>"VARCHAR(255)"
    ];
    return $estructuraTabla;
}
function definirEstructuraTablaFolios($nombreTabla){
    $estructuraTabla=[
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "unidades"=>"VARCHAR(255)",
        "dependencia"=>"VARCHAR(255)",
        "letra"=>'VARCHAR(255)',
        "ultimo_folio"=>"INT"
    ];
    return $estructuraTabla;
}

function definirEstructuraTablaSubdependencias($nombreTabla) {
    $estructuraTabla = [
        "id" => "INT AUTO_INCREMENT PRIMARY KEY",
        "subdependencia" => "VARCHAR(255)",
        "estado"=>'VARCHAR(255)',
        "notas"=>'VARCHAR(255)'
    ];
    return $estructuraTabla;
}
?>