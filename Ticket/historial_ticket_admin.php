<?php
session_start();
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit();
}
include_once '../DataBaseConexion/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de ticket no válido.";
    exit();
}

$id_ticket = $_GET['id'];

if ($DB_conn) {
    // Consulta para obtener el historial del ticket específico
    $query_historial = "SELECT h.id, h.fecha_cambio_estado, e.nombre_estado, u.nombre_usuario 
                        FROM historial_estado_tickets h
                        JOIN estados e ON h.id_estado = e.id_estado
                        JOIN usuario u ON h.id_usuario = u.id_usuario
                        WHERE h.id_ticket = $id_ticket
                        ORDER BY h.fecha_cambio_estado DESC";
    $result_historial = mysqli_query($DB_conn, $query_historial);
}
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
        <h2 class="mb-4 text-center">Historial del Ticket ID: <?php echo $id_ticket; ?></h2>
        <?php
        if ($result_historial && mysqli_num_rows($result_historial) > 0) {
            echo '<div class="scrollable-table"><table border="1">
                    <tr>
                        <th>ID Cambio</th>
                        <th>Fecha del Cambio</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                    </tr>';
            while ($row = mysqli_fetch_assoc($result_historial)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['fecha_cambio_estado']}</td>
                        <td>{$row['nombre_estado']}</td>
                        <td>{$row['nombre_usuario']}</td>
                    </tr>";
            }
            echo '</table></div>'; // Corregir aquí cerrando la etiqueta <table>
        } else {
            echo '<p>No hay historial registrado para este ticket.</p>';
        }
        mysqli_close($DB_conn);
        ?>
        <a href="mostrar_tabla_admin.php" class="btn-azul">Volver</a>
    </div>
<footer>
        <div class="container">
            <p class="mb-0">Derechos Reservados ©</p>
        </div>
    </footer>
</body>
</html>