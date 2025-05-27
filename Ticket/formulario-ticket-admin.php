<?php
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Verificar si la sesión del usuario está activa
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../Login/login.html");
    exit(); // Asegurar que el script se detenga después de la redirección
}

// Obtener el ID de usuario de la sesión
$user_id = $_SESSION['ID_usuario'];

// Consulta para determinar si el usuario es administrador
$query_admin = "SELECT tipo_usuario FROM usuario WHERE id_usuario = $user_id";
$result_admin = $DB_conn->query($query_admin);

// Verificar si se encontró información sobre si es administrador
if ($result_admin->num_rows > 0) {
    $row_admin = $result_admin->fetch_assoc();
    $tipo_usuario = $row_admin['tipo_usuario'];
    $es_administrador = ($tipo_usuario == 'administrador');
} else {
    $es_administrador = false;
}

// URL a la que se dirigirá el usuario según su rol
$url_destino = $es_administrador ? '../Panel/administradorPanel.php' : '../Panel/usuarioPanel.php';

// Consultas para obtener las opciones de las áreas, prioridades y estados
$query_prioridades = "SELECT id_prioridad, nombre_prioridad FROM prioridades";
$result_prioridades = $DB_conn->query($query_prioridades);

$query_estados = "SELECT id_estado, nombre_estado FROM estados";
$result_estados = $DB_conn->query($query_estados);

$query_areas = "SELECT id_area, nombre_area FROM areas";
$result_areas = $DB_conn->query($query_areas);
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
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-top: 10px;
        }
        body, html {
            height: 100%;
            margin: 0;
        }
    </style>

    <script>
        // Mostrar/ocultar el campo de ubicación
        function toggleUbicacionField() {
            const checkbox = document.getElementById('cargar_ubicacion');
            const ubicacionField = document.getElementById('ubicacion_ticket');
            ubicacionField.style.display = checkbox.checked ? 'block' : 'none';
            
            if (checkbox.checked) {
                setTimeout(() => {
                    map.invalidateSize();
                }, 500);
            }
        }

        // Inicializar el mapa y el marcador
        let map;
        let marker;

        function initMap() {
            const defaultLocation = { lat: -34.397, lng: 150.644 }; // Cambia las coordenadas según sea necesario
            map = L.map('map').setView([defaultLocation.lat, defaultLocation.lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            marker = L.marker([defaultLocation.lat, defaultLocation.lng], { draggable: true }).addTo(map);

            // Actualizar latitud y longitud al mover el marcador
            marker.on('dragend', function () {
                const latLng = marker.getLatLng();
                document.getElementById('ubicacion_lat').value = latLng.lat;
                document.getElementById('ubicacion_lng').value = latLng.lng;
            });
        }

        // Buscar dirección
        function buscarDireccion() {
            const direccion = document.getElementById('search-input').value;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = data[0].lat;
                        const lon = data[0].lon;
                        map.setView([lat, lon], 13);
                        marker.setLatLng([lat, lon]);

                        document.getElementById('ubicacion_lat').value = lat;
                        document.getElementById('ubicacion_lng').value = lon;
                        document.getElementById('ubicacion').value = direccion;
                    }
                });
        }
    </script>
</head>
<body onload="initMap()">
<nav class="navbar">
    <div class="container">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </div>
</nav>
<div class="container">
    <div id="container-central-formulario-ticker">
        <h2 class="mb-4 text-center">Formulario de Carga de Tickets</h2>
        <form action="procesar-carga-admin.php" method="POST">
            <div class="form-group">
                <label for="titulo_ticket">Título del Ticket:</label>
                <input type="text" class="form-control" name="titulo_ticket" required>
            </div>

            <div class="form-group">
                <label for="descripcion_ticket">Descripción del Ticket:</label>
                <textarea class="form-control" name="descripcion_ticket" required></textarea>
            </div>

            <div class="form-group">
                <label for="id_prioridad_ticket">Prioridad:</label>
                <select class="form-control" name="id_prioridad_ticket" required>
                    <?php while ($row = $result_prioridades->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_prioridad']; ?>"><?php echo $row['nombre_prioridad']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="id_area_ticket">Área:</label>
                <select class="form-control" name="id_area_ticket" required>
                    <?php while ($row = $result_areas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_area']; ?>"><?php echo $row['nombre_area']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_estado_ticket">Estado:</label>
                <select class="form-control" name="id_estado_ticket" required>
                    <?php while ($row = $result_estados->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_estado']; ?>"><?php echo $row['nombre_estado']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="cargar_ubicacion" onclick="toggleUbicacionField()">
                <label class="form-check-label" for="cargar_ubicacion">Cargar ubicación</label>
            </div>

            <div class="form-group" id="ubicacion_ticket" style="display: none;">
                <input type="text" name="search-input" id="search-input" placeholder="Buscar dirección...">
                <button type="button" onclick="buscarDireccion()">Buscar</button>
                <div id="map"></div>
                <input type="hidden" name="ubicacion_lat" id="ubicacion_lat">
                <input type="hidden" name="ubicacion_lng" id="ubicacion_lng">
                <input type="hidden" name="ubicacion" id="ubicacion">
            </div>

            <button type="submit" class="btn-azul">Cargar Ticket</button>
            <a href="<?php echo $url_destino; ?>" class="btn-morado">Volver</a>
        </form>
    </div>
</div>
<footer>
    <div class="container">
        <p class="mb-0">Derechos Reservados ©</p>
    </div>
</footer>
</body>
</html>
