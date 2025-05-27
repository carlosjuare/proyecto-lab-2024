<?php
session_start();
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit();
}

include_once '../DataBaseConexion/db.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de usuarios</title>
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
        <h2 class="mb-4 text-center">Administrar Usuarios</h2>


        <?php
        if ($DB_conn) {
            // Consultar los datos de la tabla 'usuarios' y 'areas' para obtener el nombre del área
            $query = "SELECT u.id_usuario, u.nombre_usuario, u.apellido_usuario, u.email_usuario, u.telefono_usuario, u.clave_usuario, u.tipo_usuario, a.nombre_area 
                        FROM usuario u 
                        INNER JOIN areas a ON u.id_area_usuario = a.id_area";
            $result = mysqli_query($DB_conn, $query);

            // Mostrar la tabla con los datos de 'usuarios'
            echo '<table border="1">
                    <thead>
                        <tr>
                            <th>ID Usuario</th>
                            <th>Nombre Usuario</th>
                            <th>Apellido Usuario</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Clave</th>
                            <th>Area</th>
                            <th>Tipo de usuario</th>
                            <th>Cantidad de Tickets</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                $id_usuario = $row['id_usuario'];

                // Obtener la cantidad de tickets asociados al usuario
                $query_tickets = "SELECT COUNT(*) AS cantidad_tickets FROM tickets WHERE id_usuario_ticket = $id_usuario";
                $result_tickets = mysqli_query($DB_conn, $query_tickets);
                $row_tickets = mysqli_fetch_assoc($result_tickets);
                $cantidad_tickets = $row_tickets['cantidad_tickets'];

                echo '<tr>
                        <td>' . $row['id_usuario'] . '</td>
                        <td>' . $row['nombre_usuario'] . '</td>
                        <td>' . $row['apellido_usuario'] . '</td>
                        <td>' . $row['email_usuario'] . '</td>
                        <td>' . $row['telefono_usuario'] . '</td>
                        <td>' . $row['clave_usuario'] . '</td>
                        <td>' . $row['nombre_area'] . '</td>
                        <td>' . $row['tipo_usuario'] . '</td>
                        <td>' . $cantidad_tickets . '</td>
                        <td><a href="formulario-user.php?id=' . $id_usuario . '">Editar</a></td>
                        <td>';

                // Habilitar o deshabilitar la opción de eliminar según la cantidad de tickets
                if ($cantidad_tickets == 0) {
                    echo '<a href="confirm-elim-user.php?id=' . $id_usuario . '">Eliminar</a>';
                } else {
                    echo 'Eliminar (Tickets asociados)';
                }

                echo '</td></tr>';
            }

            echo '</tbody></table>';

            // Liberar el conjunto de resultados
            mysqli_free_result($result);

            // Cerrar la conexión a la base de datos
            mysqli_close($DB_conn);

            // Botón para volver
            echo '<p class="mt-3 text-danger">ATENCIÓN: Debes tener 0 tickets en un área para poder eliminarla.</p>';
            echo '<div class="container-buttons">
                    <a href="../Panel/administradorPanel.php" class="btn-azul">Volver</a>
                   </div>';
        } else {
            echo 'Conexión fallida.';
        }
?>