<?php

// CREACIÓN DE RUTAS DINÁMICAS

// 1. Detectar el protocolo (http o https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) 
    ? "https" : "http";

// 2. Detectar el "Host" puede ser localhost o 192.168.100.14 por ejemplo
$host = $_SERVER['HTTP_HOST'];

// 3. Definir la ruta de la subcarpeta de tu proyecto
// IMPORTANTE Asegurarse de que las barras (/) deben estar correctas
$folder_path = '/Clase/estancia/';

// 4. Definir la BASE_URL (la URL completa)
define('BASE_URL', $protocol . '://' . $host . $folder_path);

// 5. Definir la ruta raíz del servidor (PROJECT_ROOT) 
// PROJECT_ROOT garantiza la ruta absoluta desde la raíz del proyecto (estancia/).
// Esto previene errores de "File not found" cuando se incluye un archivo desde un subdirectorio (Controladores o Modelos).
define('PROJECT_ROOT', dirname(__DIR__)); 

//RUTA A HERRAMIENTAS DE MYSQL 
 // Ruta a la carpeta 'bin' de MySQL en XAMPP. Es necesaria para que el BackupModel pueda encontrar 'mysqldump.exe'
 //comillas dobles para que la ruta de Windows sea correcta
define('MYSQL_BIN_PATH', "C:\\xampp\\mysql\\bin\\");

?>