<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Verificar si existe una sesión
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Verificar si se ha recibido un ID de usuario válido
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_usuario = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación de Usuario</title>
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

<div id="container-central-areas">
    <?php
                // Verificar la cantidad de tickets asociados al usuario
                $query_tickets = "SELECT COUNT(*) AS cantidad_tickets FROM tickets WHERE id_usuario_ticket = $id_usuario";
                $result_tickets = mysqli_query($DB_conn, $query_tickets);
                $row_tickets = mysqli_fetch_assoc($result_tickets);
                $cantidad_tickets = $row_tickets['cantidad_tickets'];

                if ($cantidad_tickets == 0) {
                    // Eliminar el usuario
                    $delete_query = "DELETE FROM usuario WHERE id_usuario = $id_usuario";
                    $delete_result = mysqli_query($DB_conn, $delete_query);

                    if ($delete_result) {
                        echo 'Usuario eliminado correctamente.';
                    } else {
                        echo 'Error al eliminar el usuario: ' . mysqli_error($DB_conn);
                    }
                } else {
                    echo 'El usuario tiene tickets asociados y no puede ser eliminado.';
                }
            } else {
                echo 'ID de usuario no válido.';
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($DB_conn);
        } else {
            echo 'Conexión fallida.';
        }
    } else {
        echo 'No hay sesión de usuario. Por favor, inicia sesión.';
    }
    ?>
    <br>
    <a href="../Panel/administradorPanel.php"><button class="btn-azul">Volver</button></a>
</div>

<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>