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
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #310467;
            color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
        }
        .navbar img {
            height: 40px;
            margin-left: 20px;
        }
        footer {
            background-color: #310467;
            color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
        #container-central {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }
        #container-central form {
            width: 100%;
            max-width: 600px;
        }
        #container-central button, .btn-volver-custom {
            background-color: #310467;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            padding: 10px 20px;
            margin: 10px;
            display: block;
        }
        #container-central button:hover {
            background-color: #7013ed;
        }
        select, input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #310467;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #7013ed;
        }
        .scrollable-table {
            overflow-y: auto;
            max-height: 500px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-break: break-all;
        }
        th {
            background-color: #310467;
            color: white;
        }
    </style>
</head>

<body>
<nav class="navbar">
    <div class="container">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </div>
</nav>

<div class="container">
    <div id="container-central">


<?php
// Verificar si existe una sesión y obtener el ID de usuario
if (isset($_SESSION['ID_usuario'])) {
    // Verificar si la conexión está establecida correctamente
    if ($DB_conn) {
        // Verificar si se recibieron los datos del formulario
        if (isset($_POST['id_usuario'], $_POST['nombre_usuario'], $_POST['apellido_usuario'], $_POST['email_usuario'], $_POST['telefono_usuario'], $_POST['clave_usuario'])) {
            // Sanitizar los datos recibidos del formulario para evitar inyección de SQL
            $id_usuario = mysqli_real_escape_string($DB_conn, $_POST['id_usuario']);
            $nombre_usuario = mysqli_real_escape_string($DB_conn, $_POST['nombre_usuario']);
            $apellido_usuario = mysqli_real_escape_string($DB_conn, $_POST['apellido_usuario']);
            $email_usuario = mysqli_real_escape_string($DB_conn, $_POST['email_usuario']);
            $telefono_usuario = mysqli_real_escape_string($DB_conn, $_POST['telefono_usuario']);
            $clave_usuario = mysqli_real_escape_string($DB_conn, $_POST['clave_usuario']);

            // Actualizar los datos del usuario en la base de datos
            $query_actualizar = "UPDATE usuario SET nombre_usuario='$nombre_usuario', apellido_usuario='$apellido_usuario', email_usuario='$email_usuario', telefono_usuario='$telefono_usuario', clave_usuario='$clave_usuario' WHERE id_usuario='$id_usuario'";
            $resultado_actualizar = mysqli_query($DB_conn, $query_actualizar);

            if ($resultado_actualizar) {
                echo 'Los datos del usuario se han actualizado correctamente.';
                session_destroy();
                echo '<a href="../DataBaseConexion/cerrarsesion.php" class="btn btn-primary">Volver</a>';
                // Redirige al usuario a la página de inicio de sesión
                header('Location: ../DataBaseConexion/cerrarsesion.php');
                exit();

            } else {
                echo 'Hubo un error al actualizar los datos del usuario: ' . mysqli_error($DB_conn);
            }
        } else {
            echo 'No se recibieron datos del formulario.';
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