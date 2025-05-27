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
        // Consultar los datos de la tabla 'areas' y la cantidad de tickets por área
        $query = "SELECT a.id_area, a.nombre_area, a.descripcion_area, COUNT(t.id_ticket) as cantidad_tickets
                        FROM areas a
                        LEFT JOIN tickets t ON a.id_area = t.id_area_ticket
                        GROUP BY a.id_area, a.nombre_area, a.descripcion_area";
        $result = mysqli_query($DB_conn, $query);
?>

        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Administrador de Áreas</title>
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
        <?php
        echo '<table border="1">
            <tr>
                <th>ID Área</th>
                <th>Nombre Área</th>
                <th>Descripción Área</th>
                <th>Cantidad de Tickets</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['id_area'] . '</td>
                    <td>' . $row['nombre_area'] . '</td>
                    <td>' . $row['descripcion_area'] . '</td>
                    <td>' . $row['cantidad_tickets'] . '</td>
                <td>
                    <a href="editar-areas.php?id=' . $row['id_area'] . '">Editar</a>
                </td>
            <td>';
            // Agregar lógica para permitir o no borrar el área
            if ($row['cantidad_tickets'] == 0) {
                echo '<a href="confirm-elim-area.php?id=' . $row['id_area'] . '">Eliminar</a>';
            } else {
                echo 'No permitido';
            }
            echo '</td>
        </tr>';
        }
        echo '</table>';
        // Liberar el conjunto de resultados
        mysqli_free_result($result);
        mysqli_close($DB_conn);
        // Disclaimer
        echo '<p class="mt-3 text-danger">ATENCIÓN: Debes tener 0 tickets en un área para poder eliminarla.</p>';
        echo '<div class="container-central-car-exitosa">';
        echo '    <a href="../Panel/administradorPanel.php" class="btn-morado">Volver</a>';
        echo '    <a href="formulario-area.html" class="btn-azul">Cargar Área</a>';
        echo '</div>';
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