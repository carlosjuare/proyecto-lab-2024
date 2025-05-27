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
    <title>Editar Usuario</title>
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

        // Verificar si existe una sesión
    if (isset($_SESSION['ID_usuario'])) {
     // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
    // Verificar si se ha recibido un ID de usuario válido
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];
     // Consultar los datos del usuario
    $query = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
    $result = mysqli_query($DB_conn, $query);
    // Verificar si se encontró el usuario
    if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    ?>

                        <h2>Editar Usuario</h2>
                        <form action="editar-user.php" method="post">
                            <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>" />

                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre_usuario']; ?>" required />

                            <label for="apellido">Apellido:</label>
                            <input type="text" id="apellido" name="apellido" value="<?php echo $row['apellido_usuario']; ?>" required />

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo $row['email_usuario']; ?>" required />

                            <label for="telefono">Teléfono:</label>
                            <input type="tel" id="telefono" name="telefono" value="<?php echo $row['telefono_usuario']; ?>" required />

                            <label for="tipo">Tipo de Usuario:</label>
                            <select id="tipo" name="tipo" required>
                                <option value="administrador" <?php echo ($row['tipo_usuario'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                                <option value="usuario" <?php echo ($row['tipo_usuario'] == 'usuario') ? 'selected' : ''; ?>>Usuario</option>
                            </select>

                            <label for="clave">Contraseña:</label>
                            <input type="password" id="clave" name="clave" placeholder="COMPLETAR!!" />

                            <button type="submit" class="btn-azul">Guardar Cambios</button>
                        </form>
        <?php
                    } else {
                        echo 'Usuario no encontrado.';
                    }
                } else {
                    echo 'ID de usuario no válido.';
                }
            } else {
                echo 'Conexión fallida.';
            }
        } else {
            echo 'No hay sesión de usuario. Por favor, inicia sesión.';
        }
        ?>
        <a href="../Panel/administradorPanel.php"><button class="btn-morado">Volver</button></a>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">Derechos Reservados ©</p>
        </div>
    </footer>

</body>

</html>