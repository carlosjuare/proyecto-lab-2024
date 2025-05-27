<?php
session_start();

// Verificar si la sesión del usuario no está activa, y redirigir a login.html
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit(); // Asegurar que el script se detenga después de la redirección
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Exitosa</title>
    <link rel="icon" href="../img/Okaro.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />


    <link rel="stylesheet" href="../Estilos/botones.css" />
    <link rel="stylesheet" href="../Estilos/navfoot.css" />
    <link rel="stylesheet" href="../Estilos/estilo.css" />
</head>

<body>
<nav class="navbar">
    <div class="container">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </div>
</nav>

<div class="container">
    <div id="container-central-areas">
        
    <?php
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Verificar si existe una sesión de usuario y se recibe la información del formulario
if (isset($_SESSION['ID_usuario'], $_POST['nombre_area'], $_POST['descripcion_area'], $_POST['id_area'])) {
    // Obtener la información del formulario
    $nombre_area = $_POST['nombre_area'];
    $descripcion_area = $_POST['descripcion_area'];
    $id_area = $_POST['id_area'];

    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Actualizar el área en la base de datos (consulta preparada)
        $query = "UPDATE areas SET nombre_area = ?, descripcion_area = ? WHERE id_area = ?";
        $stmt = mysqli_prepare($DB_conn, $query);
        
        // Vincular parámetros
        mysqli_stmt_bind_param($stmt, "ssi", $nombre_area, $descripcion_area, $id_area);
        
        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            echo "Área actualizada correctamente. <br>";
            echo '<a href="../AdministrarAreas/mostrar-areas.php" class="btn-azul">Volver</a>';
        } else {
            echo "Error al actualizar el área: " . mysqli_error($DB_conn);
        }

        // Cerrar la consulta preparada
        mysqli_stmt_close($stmt);
        
        // Cerrar la conexión a la base de datos
        mysqli_close($DB_conn);
        
        // No es necesario redireccionar aquí, ya que el botón proporciona la opción de ir a mostrar-areas.php
    } else {
        echo 'Conexión fallida.';
    }
} else {
    echo 'No se recibieron datos completos del formulario o no hay sesión de usuario. Por favor, inténtalo de nuevo.';
}
?>


    </div>
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>



