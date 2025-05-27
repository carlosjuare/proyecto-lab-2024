<?php
// Inicia la sesión
session_start();

// Verifica si la sesión del usuario no está activa, y redirige a login.html
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit(); // Asegura que el script se detenga después de la redirección
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
// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        $id_usuario_sesion = $_SESSION['ID_usuario'];

        // Consultar los datos del usuario actual
        $query_usuario = "SELECT * FROM usuario WHERE id_usuario = $id_usuario_sesion";
        $result_usuario = mysqli_query($DB_conn, $query_usuario);

        // Verificar si se encontraron resultados
        if (mysqli_num_rows($result_usuario) > 0) {
            $usuario = mysqli_fetch_assoc($result_usuario);

            // Mostrar los datos del usuario en un formulario editable
            echo '<h2>Editar Datos del Usuario</h2>';
            echo '<form action="procesar_cambios.php" method="POST">';
            echo '<input type="hidden" name="id_usuario" value="' . $usuario['id_usuario'] . '">';
            echo 'Nombre: <input type="text" name="nombre_usuario" value="' . $usuario['nombre_usuario'] . '"><br>';
            echo 'Apellido: <input type="text" name="apellido_usuario" value="' . $usuario['apellido_usuario'] . '"><br>';
            echo 'Email: <input type="text" name="email_usuario" value="' . $usuario['email_usuario'] . '"><br>';
            echo 'Teléfono: <input type="text" name="telefono_usuario" value="' . $usuario['telefono_usuario'] . '"><br>';
            echo 'Clave: <input type="text" name="clave_usuario" value="' . $usuario['clave_usuario'] . '"><br>';
            echo '<input type="submit" value="Guardar Cambios" class="btn-azul">';
            echo '</form>';
            echo '<div class="contento">';
            if ($usuario['tipo_usuario'] == 'administrador') {
                echo '<a href="../Panel/administradorPanel.php" class="btn-morado">Volver</a>';
            } else {
                echo '<a href="../Ticket/mostrar_usuarios.php" class="btn-morado">Volver</a>';
            }
            echo '</div>';
        } else {
            echo 'No se encontraron datos de usuario.';
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
