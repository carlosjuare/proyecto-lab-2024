<?php
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';
// Verificar si la sesión del usuario está activa
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../html/login.html");
    exit(); // Asegurar que el script se detenga después de la redirección
}
// Obtener el ID de usuario de la sesión
$user_id = $_SESSION['ID_usuario'];
// Consulta para obtener el tipo de usuario
$query_tipo_usuario = "SELECT tipo_usuario FROM usuario WHERE id_usuario = $user_id";
$result_tipo_usuario = $DB_conn->query($query_tipo_usuario);
// Verificar si se encontró el tipo de usuario
if ($result_tipo_usuario->num_rows > 0) {
    $row_tipo_usuario = $result_tipo_usuario->fetch_assoc();
    $tipo_usuario = $row_tipo_usuario['tipo_usuario'];
    // Determinar la URL de destino según el tipo de usuario
    $url_destino = ($tipo_usuario == 'administrador') ? '../AdministrarAreas/mostrar-areas.php' : '../Panel/usuarioPanel.php';
} else {
    // Si no se puede determinar el tipo de usuario, redirigir a una página de error o manejar según sea necesario
    header("Location: ../Login/login.html");
    exit();
}
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
        <div class="message">
            <h2 class="mb-4">Area creada exitosamente</h2>
            <p>¡La carga fue exitosa!</p>
        </div>
        <div class="text-center">
            <a href="<?php echo $url_destino; ?>" class="btn-azul">Seguir</a>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>