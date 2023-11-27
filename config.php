<?php
// Permitir CORS para localhost:3000 específicamente
header('Access-Control-Allow-Origin: https://difcobro.netlify.app');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

//EL HOST DONDE SE ESTA TRABAJANDO
//define("bd_name","geoesxjc_desarrollo1");
//define("bd_user","geoesxjc_des1");
//define("bd_password","desarrollo2016");
//define("bd_permite_postgis", false);

define("host_idxPage", "http://localhost/cobro/BackSistemaDeCobro/");
define("bd_use", "mysql");
define("bd_port", "3306");
define("bd_name","centrodepagos");
define("bd_host","localhost");
define("bd_user","root");
define("bd_password","");
?>