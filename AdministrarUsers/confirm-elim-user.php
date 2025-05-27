<?php
// Iniciar la sesión si aún no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la sesión del usuario está activa
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../html/login.html");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Verificar si se ha recibido un ID de ticket válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID de ticket no válido.'); window.location.href='../Panel/administradorPanel.php';</script>";
    exit();
}

$id_ticket = $_GET['id'];

// Determinar la URL de destino para redirigir después de la eliminación
$url_destino = '../eliminar_ticket_admin.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Eliminación</title>
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

<div class="container-central-areas">
    <div id="container-central-areas">
        <div class="message">
            <h2 class="mb-4">Confirmación de Eliminación</h2>
            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
        </div>
        <div class="text-center">
            <a href="eliminar-user.php?id=<?php echo $id_ticket; ?>" class="btn-rojo">Eliminar</a>
            <a href="mostrar-user.php" class="btn-azul">Cancelar</a>
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