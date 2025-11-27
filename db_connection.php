<?php

// Para ponerle la hora de Mexico 
date_default_timezone_set('America/Mexico_City');

$server = "localhost";
$user = "root";
$password = ""; 
$db = "molino"; 

// Conexión a la base de datos usando MySQLi
$conexion = new mysqli($server, $user, $password, $db);

// Verificar si la conexión a la BD falló
if ($conexion->connect_error) {
    // die termina la ejecución del script y muestra un mensaje.
    die("Conexión fallida: ". $conexion->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8 para soportar tildes y caracteres especiales
$conexion->set_charset("utf8mb4");

?>