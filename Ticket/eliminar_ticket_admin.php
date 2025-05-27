<?php
// Verificar si la sesión no está activa antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Mensaje inicializado como vacío
$mensaje = '';

// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Verificar si se ha recibido un ID de ticket válido
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_ticket = $_GET['id'];

            // Eliminar el ticket sin verificar la propiedad por el usuario de la sesión
            $delete_query = "DELETE FROM tickets WHERE id_ticket = $id_ticket";
            $delete_result = mysqli_query($DB_conn, $delete_query);

            if ($delete_result) {
                $mensaje = 'Ticket eliminado correctamente.';
            } else {
                $mensaje = 'Error al eliminar el ticket: ' . mysqli_error($DB_conn);
            }
        } else {
            $mensaje = 'ID de ticket no válido.';
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($DB_conn);
    } else {
        $mensaje = 'Conexión fallida.';
    }
} else {
    $mensaje = 'No hay sesión de usuario. Por favor, inicia sesión.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación de Ticket</title>
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
        <div class="message">
            <h2 class="mb-4"><?php echo $mensaje; ?></h2>
        </div>
        <div class="text-center">
            <a href="../Ticket/mostrar_tabla_admin.php" class="btn-azul">Seguir</a>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>