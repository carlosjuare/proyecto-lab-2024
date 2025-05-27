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
            $check_query = "SELECT * FROM ticket WHERE id_ticket = $id_ticket AND id_usuario_ticket = $id_usuario_sesion";
            $check_result = mysqli_query($DB_conn, $check_query);

            if ($check_result && mysqli_num_rows($check_result) > 0) {
                // Obtener los datos actuales del ticket
                $ticket_data = mysqli_fetch_assoc($check_result);

                // Verificar si se ha enviado un formulario de edición
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Recuperar los datos del formulario
                    $nuevo_titulo = mysqli_real_escape_string($DB_conn, $_POST['nuevo_titulo']);
                    $nueva_descripcion = mysqli_real_escape_string($DB_conn, $_POST['nueva_descripcion']);

                    // Actualizar los datos del ticket
                    $update_query = "UPDATE ticket SET titulo_ticket = '$nuevo_titulo', descripcion_ticket = '$nueva_descripcion' WHERE id_ticket = $id_ticket";
                    $update_result = mysqli_query($DB_conn, $update_query);

                    if ($update_result) {
                        echo 'Ticket actualizado correctamente.';
                    } else {
                        echo 'Error al actualizar el ticket: ' . mysqli_error($DB_conn);
                    }
                } else {
                    // Mostrar el formulario de edición
                    echo '<form method="post">
                            <label for="nuevo_titulo">Nuevo Título:</label>
                            <input type="text" name="nuevo_titulo" value="' . $ticket_data['titulo_ticket'] . '" required>
                            <br>
                            <label for="nueva_descripcion">Nueva Descripción:</label>
                            <textarea name="nueva_descripcion" required>' . $ticket_data['descripcion_ticket'] . '</textarea>
                            <br>
                            <input type="submit" value="Actualizar">
                        </form>';
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
