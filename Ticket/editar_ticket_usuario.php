<?php
// Verificar si la sesión no está activa antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Edición de Tickets</title>
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
<h2 class="mb-4 text-center">Ticket editado correctamente</h2>
<?php
// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Verificar si se ha recibido un ID de ticket válido
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_ticket = $_GET['id'];
            $id_usuario_sesion = $_SESSION['ID_usuario'];
            // Verificar que el ticket pertenece al usuario actual
            $check_query = "SELECT * FROM tickets WHERE id_ticket = $id_ticket AND id_usuario_ticket = $id_usuario_sesion";
            $check_result = mysqli_query($DB_conn, $check_query);
            if ($check_result && mysqli_num_rows($check_result) > 0) {
                // Obtener los datos actuales del ticket
                $ticket_data = mysqli_fetch_assoc($check_result);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Captura el estado actual del ticket antes de la actualización
                    $estado_actual_query = "SELECT id_estado_ticket FROM tickets WHERE id_ticket = $id_ticket";
                    $estado_actual_result = mysqli_query($DB_conn, $estado_actual_query);
                    $estado_actual_data = mysqli_fetch_assoc($estado_actual_result);
                    $estado_anterior = $estado_actual_data['id_estado_ticket'];

                    // Resto del código para actualizar el ticket
                    $nuevo_titulo = mysqli_real_escape_string($DB_conn, $_POST['nuevo_titulo']);
                    $nueva_descripcion = mysqli_real_escape_string($DB_conn, $_POST['nueva_descripcion']);
                  
                    $id_prioridad = mysqli_real_escape_string($DB_conn, $_POST['id_prioridad']);
                    $id_estado = mysqli_real_escape_string($DB_conn, $_POST['id_estado']);

                    $update_query = "UPDATE tickets 
                                    SET titulo_ticket = '$nuevo_titulo', 
                                    descripcion_ticket = '$nueva_descripcion', 
                                   
                                    id_prioridad_ticket = $id_prioridad,
                                    id_estado_ticket = $id_estado
                                    WHERE id_ticket = $id_ticket";
                    $update_result = mysqli_query($DB_conn, $update_query);

                    if ($update_result) {
                        // Si la actualización fue exitosa, insertamos el registro en el historial
                        $id_usuario = $_SESSION['ID_usuario']; // Asumiendo que el ID de usuario está almacenado en la sesión
                        $insert_historial_query = "
                            INSERT INTO historial_estado_tickets (id_ticket, id_estado_anterior, id_estado, id_usuario) 
                            VALUES ($id_ticket, $estado_anterior, $id_estado, $id_usuario)";
                        $insert_historial_result = mysqli_query($DB_conn, $insert_historial_query);

                        if ($insert_historial_result) {
                            
                        } else {
                            echo 'Error al insertar en el historial: ' . mysqli_error($DB_conn);
                        }
                        echo '<a href="../Ticket/mostrar_tabla_usuario.php" class="btn-morado content" >Volver</a>';
                    } else {
                        echo 'Error al actualizar el ticket: ' . mysqli_error($DB_conn);
                        echo '<a href="../Panel/usuarioPanel.php" class="btn-morado content">Volver</a>';
                    }
                } else {
                    $query_areas = "SELECT id_area, nombre_area FROM areas";
                            $result_areas = mysqli_query($DB_conn, $query_areas);

                            
                            $query_prioridades = "SELECT id_prioridad, nombre_prioridad FROM prioridades";
                            $result_prioridades = mysqli_query($DB_conn, $query_prioridades);

                            
                            $query_estados = "SELECT id_estado, nombre_estado FROM estados";
                            $result_estados = mysqli_query($DB_conn, $query_estados);

                        // Mostrar el formulario de edición
                            echo '<form method="post">
                            <label for="nuevo_titulo">Nuevo Título:</label>
                            <input type="text" name="nuevo_titulo" value="' . $ticket_data['titulo_ticket'] . '" required>
                            <br>
                            <label for="nueva_descripcion">Nueva Descripción:</label>
                            <textarea name="nueva_descripcion" required>' . $ticket_data['descripcion_ticket'] . '</textarea>
                            <br>';

                    // Desplegable de Áreas
                   

                    // Desplegable de Prioridades
                    echo '<label for="id_prioridad">Prioridad:</label>
                            <select class="form-control" name="id_prioridad" required>';
                    while ($row = mysqli_fetch_assoc($result_prioridades)) {
                        $selected = ($row['id_prioridad'] == $ticket_data['id_prioridad_ticket']) ? 'selected' : '';
                        echo '<option value="' . $row['id_prioridad'] . '" ' . $selected . '>' . $row['nombre_prioridad'] . '</option>';
                    }
                    echo '</select>
                            <br>';

                    // Desplegable de Estados
                    echo '<label for="id_estado">Estado:</label>
                            <select class="form-control" name="id_estado" required>';
                    while ($row = mysqli_fetch_assoc($result_estados)) {
                        $selected = ($row['id_estado'] == $ticket_data['id_estado_ticket']) ? 'selected' : '';
                        echo '<option value="' . $row['id_estado'] . '" ' . $selected . '>' . $row['nombre_estado'] . '</option>';
                    }
                    echo '</select>
                    <br>
                    <div class="button-container content">
                        <input type="submit" class="btn-azul" value="Actualizar">
                        <a href="../Ticket/mostrar_tabla_usuario.php" class="btn-morado">Volver</a>
                    </div>
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
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>