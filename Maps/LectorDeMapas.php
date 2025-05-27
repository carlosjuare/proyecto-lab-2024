<?php
// Iniciar sesión y verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit();
}

// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['ID_usuario'];

// Consulta para obtener el tipo de usuario
$query_usuario = "SELECT tipo_usuario FROM usuario WHERE ID_usuario = $id_usuario";
$resultado_usuario = mysqli_query($DB_conn, $query_usuario);

// Verificar si la consulta se ejecutó correctamente
if (!$resultado_usuario) {
    echo "Error en la consulta: " . mysqli_error($DB_conn);
    exit();
}

// Obtener el tipo de usuario
if (mysqli_num_rows($resultado_usuario) > 0) {
    $fila_usuario = mysqli_fetch_assoc($resultado_usuario);
    $tipo_usuario = $fila_usuario['tipo_usuario'];
} else {
    echo "Usuario no encontrado.";
    exit();
}

// Verificar si se recibe el id_ticket por GET
if (isset($_GET['id_ticket']) && is_numeric($_GET['id_ticket'])) {
    $id_ticket = $_GET['id_ticket'];

    // Consulta para obtener la ubicación asociada al ticket
    $query = "SELECT ubicaciones.direccion, ubicaciones.latitud, ubicaciones.longitud
              FROM tickets
              LEFT JOIN ubicaciones ON tickets.id_ubicacion = ubicaciones.id_ubicacion
              WHERE tickets.id_ticket = $id_ticket";
    $result = mysqli_query($DB_conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $direccion = $row['direccion'];
        $latitud = $row['latitud'];
        $longitud = $row['longitud'];
    } else {
        echo "Ubicación no encontrada para este ticket.";
        exit();
    }
} else {
    echo "ID de ticket no válido.";
    exit();
}

// Cerrar la conexión
mysqli_close($DB_conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Ubicación</title>
    <link rel="icon" href="../img/Okaro.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../Estilos/botones.css" />
    <link rel="stylesheet" href="../Estilos/navfoot.css" />
    <link rel="stylesheet" href="../Estilos/estilo.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</head>
<body>
<nav class="navbar">
    <div class="container">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-3">Ubicación del Ticket</h2>
    <p><strong>Dirección:</strong> <?php echo $direccion; ?></p>

    <!-- Mapa donde se mostrará la ubicación -->
    <div id="map" style="width: 100%; height: 400px;"></div>

    <!-- Botón de Volver -->
    <a href="<?php echo ($tipo_usuario === 'administrador') ? '../Ticket/mostrar_tabla_admin.php' : '../Ticket/mostrar_tabla_usuario.php'; ?>" class="btn btn-morado">Volver</a>
</div>

<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>

<script>
    // Inicializar el mapa con las coordenadas desde la base de datos
    var latitud = <?php echo $latitud; ?>;
    var longitud = <?php echo $longitud; ?>;
    
    // Crear el mapa y centrarlo en la ubicación
    var map = L.map('map').setView([latitud, longitud], 15);
    
    // Cargar las capas de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Agregar un marcador en la ubicación específica
    L.marker([latitud, longitud]).addTo(map)
        .bindPopup('Ubicación del ticket: <?php echo $direccion; ?>')
        .openPopup();
</script>

</body>
</html>
