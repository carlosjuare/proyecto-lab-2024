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
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../html/login.html");
    exit();
}

// Verificar si se ha recibido un ID de ticket válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $mensaje = 'ID de ticket no válido.';
} else {
    $id_ticket = $_GET['id'];
    $id_usuario_sesion = $_SESSION['ID_usuario'];

    // Verificar que el ticket pertenece al usuario actual antes de eliminar
    $check_query = "SELECT * FROM tickets WHERE id_ticket = $id_ticket AND id_usuario_ticket = $id_usuario_sesion";
    $check_result = mysqli_query($DB_conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Obtener el id_ubicacion asociado al ticket antes de eliminarlo
        $ubicacion_query = "SELECT id_ubicacion FROM tickets WHERE id_ticket = $id_ticket";
        $ubicacion_result = mysqli_query($DB_conn, $ubicacion_query);
        $ubicacion_data = mysqli_fetch_assoc($ubicacion_result);
        $id_ubicacion = $ubicacion_data['id_ubicacion'];

        // Proceder a eliminar el ticket ya que pertenece al usuario
        $delete_ticket_query = "DELETE FROM tickets WHERE id_ticket = $id_ticket";
        $delete_ticket_result = mysqli_query($DB_conn, $delete_ticket_query);

        if ($delete_ticket_result) {
            // Verificar si el ticket tenía una ubicación asociada y eliminarla
            if (!is_null($id_ubicacion)) {
                $delete_ubicacion_query = "DELETE FROM ubicaciones WHERE id_ubicacion = $id_ubicacion";
                mysqli_query($DB_conn, $delete_ubicacion_query); // No es necesario verificar el resultado, ya que puede ser opcional
            }
            $mensaje = 'Ticket y ubicación eliminados correctamente.';
        } else {
            $mensaje = 'Error al eliminar el ticket: ' . mysqli_error($DB_conn);
        }
    } else {
        $mensaje = 'El ticket no pertenece al usuario actual o no existe.';
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($DB_conn);
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
<div id="container-central-areas">
    <h2><?php echo $mensaje; ?></h2>
    <a href="../Ticket/mostrar_tabla_usuario.php" class="btn-morado content">Volver</a>
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>
