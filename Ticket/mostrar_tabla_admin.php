<?php 
session_start();
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit();
}
include_once '../DataBaseConexion/db.php';

if ($DB_conn) {
    $query = "SELECT tickets.id_ticket, tickets.titulo_ticket, tickets.descripcion_ticket, 
                    areas.nombre_area, prioridades.nombre_prioridad, estados.nombre_estado, 
                    estados.imagen_estado, ubicaciones.direccion
              FROM tickets
              LEFT JOIN areas ON tickets.id_area_ticket = areas.id_area
              LEFT JOIN estados ON tickets.id_estado_ticket = estados.id_estado
              LEFT JOIN prioridades ON tickets.id_prioridad_ticket = prioridades.id_prioridad
              LEFT JOIN ubicaciones ON tickets.id_ubicacion = ubicaciones.id_ubicacion";
    
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
                            <th>Prioridad</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                $imgData = base64_encode($row['imagen_estado']);
                echo "<tr>
                        <td>{$row['id_ticket']}</td>
                        <td>{$row['titulo_ticket']}</td>
                        <td>{$row['descripcion_ticket']}</td>
                        <td>{$row['nombre_area']}</td>
                        <td>{$row['nombre_prioridad']}</td>
                        <td>";

                // Mostrar "Ver Ubicación" si hay una dirección
                if (!empty($row['direccion'])) {
                    echo '<a href="../Maps/LectorDeMapas.php?id_ticket=' . $row['id_ticket'] . '">Ver Ubicación</a>';
                } else {
                    echo 'No';
                }

                echo "</td>
                        <td><img src='data:image/png;base64,{$imgData}' title='{$row['nombre_estado']}' alt='Estado' style='width: 40px; height: 40px;'></td>
                        <td>
                            <a href='editar_ticket_admin.php?id={$row['id_ticket']}'>Editar</a>
                            <a href='historial_ticket_admin.php?id={$row['id_ticket']}'>Ver Historial</a>
                            <a href='Confirm_eliminarticket_admin.php?id={$row['id_ticket']}'>Eliminar</a>
                        </td>
                    </tr>";
            }
            echo '</table></div>';
            mysqli_close($DB_conn);
        } else {
            echo 'Conexión fallida.';
        }
        ?>
        
        <div class="container-buttons-areas">
            <a href="../Panel/administradorPanel.php" class="btn-azul">Volver</a>
            <a href="../Filtros/FormularioArea.php" class="btn-morado">Filtrar por área</a>
            <a href="../Filtros/FormularioPrioridad.php" class="btn-morado">Filtrar por prioridad</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">Derechos Reservados ©</p>
        </div>
    </footer>
</body>
</html>