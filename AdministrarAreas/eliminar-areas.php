<?php
    // Verificar si la sesión no está activa antes de iniciarla
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Incluir el archivo de conexión a la base de datos
    include_once '../DataBaseConexion/db.php';

    // Verificar si existe una sesión
    if (isset($_SESSION['ID_usuario'])) {
        // Verificar si la conexión está establecida correctamente
        if ($DB_conn) {
            // Verificar si se ha recibido un ID de área válido
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación de Área</title>
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
    // Verificar si la sesión no está activa antes de iniciarla
                $id_area = $_GET['id'];

                // Eliminar el área
                $delete_query = "DELETE FROM areas WHERE id_area = $id_area";
                $delete_result = mysqli_query($DB_conn, $delete_query);

                if ($delete_result) {
                    echo 'Área eliminada correctamente.';
                } else {
                    echo 'Error al eliminar el área: ' . mysqli_error($DB_conn);
                }
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
    <br>
    <a href="../AdministrarAreas/mostrar-areas.php"><button class="btn btn-primary btn-flex rounded-pill btn-volver-custom mr-2">Volver</button></a>
</div>

<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>
