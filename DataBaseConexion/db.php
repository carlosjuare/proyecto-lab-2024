<?php
// Establecer la posibilidad de utilizar variables de sesión

// Verificar si la sesión no está activa antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}


// Definir una constante para usar como salto de línea
define("NEWLINE", "<br>");

/*Conexión para el servidor live
$servername = "localhost";
$username = "okaro_admin";
$password = "iSSC0nf3d3r4t3";
$dbname = "okaro_admin";
*/

// Conexión de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finallab";


// Conexión a la base de datos
$DB_conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($DB_conn->connect_error) {
    throw new Exception("Error de conexión a MySQL: " . $DB_conn->connect_error);
}
?>