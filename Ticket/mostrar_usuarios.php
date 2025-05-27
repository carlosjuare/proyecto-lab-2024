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

// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        $id_usuario_sesion = $_SESSION['ID_usuario'];

        // Consultar los datos del usuario actual
        $query_usuario = "SELECT * FROM usuario WHERE id_usuario = $id_usuario_sesion";
        $result_usuario = mysqli_query($DB_conn, $query_usuario);

        // Consultar el nombre del área del usuario
        if (mysqli_num_rows($result_usuario) > 0) {
            $usuario = mysqli_fetch_assoc($result_usuario);
            $id_area_usuario = $usuario['id_area_usuario'];

            $query_area = "SELECT nombre_area FROM areas WHERE id_area = $id_area_usuario";
            $result_area = mysqli_query($DB_conn, $query_area);
            $area = mysqli_fetch_assoc($result_area);
            $nombre_area = $area['nombre_area'];
        }

        ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Carga de Tickets</title>
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
        // Verificar si se encontraron resultados
        if (mysqli_num_rows($result_usuario) > 0) {
            // Mostrar los datos del usuario
            echo '<h2>Datos del Usuario</h2>';
            echo '<table border="1" class="contento">
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Clave</th>
                        <th>Área</th>
                        <th></th>
                    </tr>';
            echo '<tr>
                    <td>' . $usuario['id_usuario'] . '</td>
                    <td>' . $usuario['nombre_usuario'] . '</td>
                    <td>' . $usuario['apellido_usuario'] . '</td>
                    <td>' . $usuario['email_usuario'] . '</td>
                    <td>' . $usuario['telefono_usuario'] . '</td>
                    <td>' . $usuario['clave_usuario'] . '</td>
                    <td>' . $nombre_area . '</td>
                    <td>
                    <a href="editar_usuario.php?id=' . $usuario['id_usuario'] . '">Editar</a>
                    </td>
                </tr>';
            echo '</table>';
            echo '<div class="contento">';
            if ($usuario['tipo_usuario'] == 'administrador') {
                echo '<a href="../Panel/administradorPanel.php" class="btn-morado contento">Volver</a>';
            } else {
                echo '<a href="../Panel/usuarioPanel.php" class="btn-morado contento">Volver</a>';
            }
            echo '</div>';
        } else {
            echo 'No se encontraron datos de usuario.';
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

<?php
    } else {
        echo 'Conexión fallida.';
    }
} else {
    echo 'No hay sesión de usuario. Por favor, inicia sesión.';
}
?>
