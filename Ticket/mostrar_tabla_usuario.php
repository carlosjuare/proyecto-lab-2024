<?php
    session_start();
    if (!isset($_SESSION['ID_usuario'])) {
        header("Location: ../Login/login.html");
        exit();
    }
    include_once '../DataBaseConexion/db.php';
    if ($DB_conn) {
        $id_usuario_sesion = $_SESSION['ID_usuario'];
        $query = "SELECT tickets.id_ticket, tickets.titulo_ticket, tickets.descripcion_ticket, 
        areas.nombre_area, prioridades.nombre_prioridad AS nombre_prioridad, estados.nombre_estado,
        ubicaciones.direccion
        FROM tickets
        LEFT JOIN areas ON tickets.id_area_ticket = areas.id_area
        LEFT JOIN prioridades ON tickets.id_prioridad_ticket = prioridades.id_prioridad
        LEFT JOIN estados ON tickets.id_estado_ticket = estados.id_estado
        LEFT JOIN ubicaciones ON tickets.id_ubicacion = ubicaciones.id_ubicacion
        WHERE tickets.id_usuario_ticket = $id_usuario_sesion";
        $result = mysqli_query($DB_conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
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
<h2 class="mb-4 text-center">Gestión de Tickets</h2>
<?php

        echo '<div class="scrollable-table"><table border="1">
                <tr>
                    <th>ID Ticket</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Área</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Ubicación</th>
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
                    <td>';
                    
                    // Si hay una ubicación asociada, mostrar el botón
                    if (!empty($row['direccion'])) {
                        echo '<a href="../Maps/LectorDeMapas.php?id_ticket=' . $row['id_ticket'] . '" >Ver Ubicación</a>';
                    } else {
                        echo 'No';
                    }

            echo '</td>
                    <td>
                        <a href="editar_ticket_usuario.php?id=' . $row['id_ticket'] . '">Editar</a>
                        <a href="historial_ticket_usuario.php?id=' . $row['id_ticket'] . '">Ver historial</a>
                        <a href="Confirm_eliminarticket_usuario.php?id=' . $row['id_ticket'] . '">Eliminar</a>
                    </td>
                </tr>';
        }
        echo '</table></div>';
        mysqli_close($DB_conn);
    } else {
        echo 'Conexión fallida.';
    }
?>
    <!-- Botón de Volver con margen superior -->
    <a href="../Panel/usuarioPanel.php" class="btn-morado">Volver</a>
</div>

<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>
