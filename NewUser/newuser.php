<?php
include('../DataBaseConexion/db.php');

// Verificar si los datos del formulario fueron enviados
if(isset($_POST['nombre_usuario'], $_POST['apellido_usuario'], $_POST['email_usuario'], $_POST['telefono_usuario'], $_POST['clave_usuario'],$_POST['area_usuario'])) {
    // Recuperar los datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellido_usuario = $_POST['apellido_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $telefono_usuario = $_POST['telefono_usuario'];
    $clave_usuario = $_POST['clave_usuario']; 
    $area_usuario = $_POST['area_usuario']; 

    // Preparar la consulta SQL
    $query = "INSERT INTO usuario (nombre_usuario, apellido_usuario, email_usuario, telefono_usuario, clave_usuario, tipo_usuario,id_area_usuario )
    VALUES ('$nombre_usuario', '$apellido_usuario', '$email_usuario', '$telefono_usuario', '$clave_usuario', 'usuario', '$area_usuario')";

    // Ejecutar la consulta SQL
    $rs = mysqli_query($DB_conn, $query);

    // Verificar si la consulta fue exitosa
    if(!$rs) {
        echo "Error: No se pudo insertar en la base de datos MySQL." . PHP_EOL;
        echo "Nro de error: " . mysqli_connect_errno() . PHP_EOL;
        echo "Mensaje de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    // Redirigir a la página de logueo después de insertar el usuario
    header('Location: ../Login/login.html');
    exit;
} else {
    // Si los datos del formulario no fueron enviados, mostrar un mensaje de error
    echo "Error: Los datos del formulario no fueron recibidos correctamente.";
}
?>


