<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tickets</title>
    <link rel="icon" href="../img/Okaro.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #310467;
            color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }
        .navbar img {
            height: 40px;
            margin-left: 20px;
        }
        #container-central {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            min-height: calc(100vh - 100px); /* Ajustado para mantener consistencia con la página de referencia */
        }
        #container-central form {
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }
        #container-central button, input[type="submit"] {
            background-color: #310467;
            color: white;
            border: none;
            border-radius: 4px; /* Ajustado para coincidir con el estilo de referencia */
            cursor: pointer;
            padding: 12px 20px; /* Ajustado para coincidir con el estilo de referencia */
            margin-top: 10px;
        }
        #container-central button:hover, input[type="submit"]:hover {
            background-color: #7013ed;
        }
        select, input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        footer {
            background-color: #310467;
            color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
            position: fixed; /* Ajustado para mantener consistencia con la página de referencia */
            bottom: 0;
            width: 100%;
        }
        a.volver {
            background-color: #000000;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none; /* Elimina el subrayado predeterminado del enlace */
        display: inline-block; /* Permite aplicar estilos de ancho y alto */
    }
    
    /* Cambio de color cuando se pasa el mouse */
    a.volver:hover {
        background-color: #7013ed;
    }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="container">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </div>
</nav>

<div id="container-central">
<h2 class="mb-4 text-center">Editar Tickets</h2>
<?php
// Verificar si la sesión no está activa antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';
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
                $ticket_data = mysqli_fetch_assoc($check_result);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nuevo_titulo = mysqli_real_escape_string($DB_conn, $_POST['nuevo_titulo']);
                    $nueva_descripcion = mysqli_real_escape_string($DB_conn, $_POST['nueva_descripcion']);

                    $id_area = mysqli_real_escape_string($DB_conn, $_POST['id_area']);
                    $id_prioridad = mysqli_real_escape_string($DB_conn, $_POST['id_prioridad']);
                    $id_estado = mysqli_real_escape_string($DB_conn, $_POST['id_estado']);

                    $update_query = "UPDATE tickets 
                                     SET titulo_ticket = '$nuevo_titulo', 
                                         descripcion_ticket = '$nueva_descripcion', 
                                         id_area_ticket = $id_area,
                                         id_prioridad_ticket = $id_prioridad,
                                         id_estado_ticket = $id_estado
                                     WHERE id_ticket = $id_ticket";
                    $update_result = mysqli_query($DB_conn, $update_query);

                    if ($update_result) {
                        echo 'Ticket actualizado correctamente.';
                        echo '<a href="../Panel/administradorPanel.php" class="btn btn-primary btn-flex rounded-pill mt-3">Volver</a>';
                    } else {
                        echo 'Error al actualizar el ticket: ' . mysqli_error($DB_conn);
                    }
                } else {
                    // Obtener las opciones de áreas y mostrarlas en el desplegable
                    $query_areas = "SELECT id_area, nombre_area FROM areas";
                    $result_areas = mysqli_query($DB_conn, $query_areas);

                    // Obtener las opciones de prioridades y mostrarlas en el desplegable
                    $query_prioridades = "SELECT id_prioridad, nombre_prioridad FROM prioridades";
                    $result_prioridades = mysqli_query($DB_conn, $query_prioridades);

                    // Obtener las opciones de estados y mostrarlas en el desplegable
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
                    echo '<label for="id_area">Área:</label>
                            <select class="form-control" name="id_area" required>';
                    while ($row = mysqli_fetch_assoc($result_areas)) {
                        $selected = ($row['id_area'] == $ticket_data['id_area_ticket']) ? 'selected' : '';
                        echo '<option value="' . $row['id_area'] . '" ' . $selected . '>' . $row['nombre_area'] . '</option>';
                    }
                    echo '</select>
                            <br>';

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
                            <input type="submit" value="Actualizar">
                        </form>';
                }
            } else {
                echo 'El ticket no pertenece al usuario actual.';
               
                    echo '<a href="../Panel/administradorPanel.php" class="volver">Volver</a>';
                
            }
        } else {
            echo 'ID de ticket no válido.';
        }

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