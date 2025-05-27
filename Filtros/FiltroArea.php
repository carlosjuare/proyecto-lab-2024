<?php
// Inicia la sesión (asegúrate de hacerlo al principio del archivo PHP)
session_start();

// Verifica si la sesión del usuario no está activa, y redirige a login.html
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit(); // Asegura que el script se detenga después de la redirección
}

// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Verificar si se ha enviado un área válida
        if (isset($_POST['area']) && is_numeric($_POST['area'])) {
            $id_area = $_POST['area'];

            // Consultar los datos de la tabla 'tickets' para el área seleccionada
            $query = "SELECT tickets.id_ticket, tickets.titulo_ticket, tickets.descripcion_ticket, 
                        areas.nombre_area, prioridades.nombre_prioridad, estados.nombre_estado
                        FROM tickets
                        LEFT JOIN areas ON tickets.id_area_ticket = areas.id_area
                        LEFT JOIN estados ON tickets.id_estado_ticket = estados.id_estado
                        LEFT JOIN prioridades ON tickets.id_prioridad_ticket = prioridades.id_prioridad
                        WHERE id_area_ticket = $id_area";
            $result = mysqli_query($DB_conn, $query);
            ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Áreas</title>
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
    // Mostrar la tabla con los datos de 'tickets'
    echo '<h2 class="mb-4 text-center">Tickets del Área Seleccionada</h2>';
    echo '<div class="scrollable-table"><table border="1">
        <tr>
            <th>ID Ticket</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Área</th>
            <th>Prioridad</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>';
    while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['id_ticket'] . '</td>
                    <td>' . $row['titulo_ticket'] . '</td>
                    <td>' . $row['descripcion_ticket'] . '</td>
                    <td>' . $row['nombre_area'] . '</td>
                    <td>' . $row['nombre_prioridad'] . '</td>
                    <td>' . $row['nombre_estado'] . '</td>
                <td>
                    <a href="../Ticket/editar_ticket_admin.php?id=' . $row['id_ticket'] . '">Editar</a>
                    <a href="../Ticket/Confirm_eliminarticket_admin.php?id=' . $row['id_ticket'] . '">Eliminar</a>
                    </td>
                </tr>';
            }
            echo '</table></div>';
            // Liberar el conjunto de resultados
            mysqli_free_result($result);
            // Botón para volver
            echo '<div>_</div>';
            echo '<div class="container-buttons-top">
                    <a href="../Ticket/mostrar_tabla_admin.php" class="btn-azul">Volver</a>
                </div>';
        } else {
            echo 'ID de área no válido.';
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
