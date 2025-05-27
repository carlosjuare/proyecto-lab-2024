<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Tickets por Prioridad</title>
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
        <h2 class="mb-4 text-center">Consulta de Tickets por Prioridad</h2>

        <?php
            // Verificar si se ha enviado el formulario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Obtener el valor de la prioridad seleccionada
                $prioridadSeleccionada = $_POST['prioridad'];

                // Incluir el archivo de conexión a la base de datos
                include_once '../DataBaseConexion/db.php';

                // Verificar si la conexión está establecida correctamente
                if ($DB_conn) {
                    // Preparar la consulta SQL
                   // $query = "SELECT * FROM tickets WHERE id_prioridad_ticket = (
                          //      SELECT id_prioridad FROM prioridades WHERE id_prioridad = $prioridadSeleccionada
                          //  )";
                                $query = "SELECT tickets.id_ticket, tickets.titulo_ticket, tickets.descripcion_ticket, 
                                areas.nombre_area, prioridades.nombre_prioridad, estados.nombre_estado
                                FROM tickets
                                LEFT JOIN areas ON tickets.id_area_ticket = areas.id_area
                                LEFT JOIN estados ON tickets.id_estado_ticket = estados.id_estado
                                LEFT JOIN prioridades ON tickets.id_prioridad_ticket = prioridades.id_prioridad
                                WHERE id_prioridad_ticket = $prioridadSeleccionada";

                    // Ejecutar la consulta
                    $result = mysqli_query($DB_conn, $query);

                    if ($result) {
                        // Mostrar los resultados en una tabla
                        echo '<div class="table-container">';
                        echo '<table border="1">
                                <tr>
                                    <th>ID Ticket</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Área</th>
                                    <th>Prioridad</th>
                                    <th>Acciones</th>
                                    <th>Estado</th>
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
                        echo '<div>_</div>';
                        echo '    <a href="../Ticket/mostrar_tabla_admin.php" class="btn-azul">Volver</a>';

                        // Liberar el conjunto de resultados
                        mysqli_free_result($result);
                    } else {
                        echo 'Error en la consulta: ' . mysqli_error($DB_conn);
                    }

                    // Cerrar la conexión a la base de datos
                    mysqli_close($DB_conn);
                } else {
                    echo 'Conexión fallida.';
                }
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
