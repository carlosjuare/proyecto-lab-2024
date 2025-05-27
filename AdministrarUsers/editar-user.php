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
    <title>Carga Exitosa</title>
    <link rel="icon" href="../img/Okaro.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./admUser.css"/>   

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

<div class="container">
    <div id="container-central-areas">
        

<?php
// Verificar si existe una sesión
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener los datos del formulario
            $id_usuario = $_POST['id_usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $tipo = $_POST['tipo'];
            $clave = $_POST['clave'];

            // Actualizar los campos del usuario
            $update_usuario_query = "UPDATE usuario SET nombre_usuario = ?, apellido_usuario = ?, email_usuario = ?, telefono_usuario = ?, tipo_usuario = ?, clave_usuario = ? WHERE id_usuario = ?";
            $stmt = mysqli_prepare($DB_conn, $update_usuario_query);
            mysqli_stmt_bind_param($stmt, 'ssssssi', $nombre, $apellido, $email, $telefono, $tipo, $clave, $id_usuario);

            if (mysqli_stmt_execute($stmt)) {
                echo 'Usuario actualizado correctamente.';
                echo '<a href="../AdministrarUsers/mostrar-user.php" class="btn-azul">Volver</a>';
            } else {
                echo 'Error al actualizar el usuario: ' . mysqli_error($DB_conn);
            }

            // Cerrar la conexión a la base de datos
            mysqli_close($DB_conn);
        } else {
            echo 'Método no permitido.';
        }
    } else {
        echo 'Conexión fallida.';
    }
} else {
    echo 'No hay sesión de usuario. Por favor, inicia sesión.';
}
?>


    </div>
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>