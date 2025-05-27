<?php
// Verificar si la sesión no está activa antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión a la base de datos
include_once '../php/db.php';

// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Verificar si se ha recibido un ID de ticket válido
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_ticket = $_GET['id'];
            $id_usuario_sesion = $_SESSION['ID_usuario'];

            // Verificar que el ticket pertenece al usuario actual
            $check_query = "SELECT 1 FROM ticket WHERE id_ticket = $id_ticket AND id_usuario_ticket = $id_usuario_sesion";
            $check_result = mysqli_query($DB_conn, $check_query);

            if ($check_result && mysqli_num_rows($check_result) > 0) {
                // Eliminar el ticket
                $delete_query = "DELETE FROM ticket WHERE id_ticket = $id_ticket";
                $delete_result = mysqli_query($DB_conn, $delete_query);

                if ($delete_result) {
                    echo 'Ticket eliminado correctamente.';
                } else {
                    echo 'Error al eliminar el ticket: ' . mysqli_error($DB_conn);
                }
            } else {
                echo 'El ticket no pertenece al usuario actual.';
            }
        } else {
            echo 'ID de ticket no válido.';
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
