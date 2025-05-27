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
    // Verificar si el tipo de usuario es administrador
    $es_administrador = ($tipo_usuario == 'administrador');
} else {
    // Si no se puede determinar si es administrador, asumir que no lo es
    $es_administrador = false;
}

// URL a la que se dirigirá el usuario según su rol
$url_destino = $es_administrador ? '../Panel/administradorPanel.php' : '../Panel/usuarioPanel.php';

// Consultas para obtener las opciones de las áreas, prioridades y estados
$query_prioridades = "SELECT id_prioridad, nombre_prioridad FROM prioridades";
$result_prioridades = $DB_conn->query($query_prioridades);

$query_estados = "SELECT id_estado, nombre_estado FROM estados";
$result_estados = $DB_conn->query($query_estados);
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

    <!-- Agrega el script de Leaflet y sus estilos -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Estilos personalizados para el mapa -->
    <style>
        #map {
            height: 400px; /* Puedes aumentar esto si lo necesitas más grande */
            width: 100%;
            margin-top: 10px;
        }
        /* Hacer que el contenedor ocupe más espacio si quieres pantalla completa */
        body, html {
            height: 100%;
            margin: 0;
        }
    </style>

    <!-- Script para mostrar/ocultar el campo de ubicación -->
    <script>
        function toggleUbicacionField() {
            const checkbox = document.getElementById('cargar_ubicacion');
            const ubicacionField = document.getElementById('ubicacion_ticket');
            ubicacionField.style.display = checkbox.checked ? 'block' : 'none';
            
            if (checkbox.checked) {
                setTimeout(() => {
                    map.invalidateSize(); // Ajustar el mapa si se ha mostrado
                }, 500);
            }
        }
    </script>

    <!-- Script para inicializar el mapa y búsqueda de dirección -->
    <script>
        let map;
        let marker;

        function initMap() {
            const defaultLocation = { lat: -34.397, lng: 150.644 }; // Cambia las coordenadas según sea necesario

            // Crear el mapa en el div con id="map"
            map = L.map('map').setView([defaultLocation.lat, defaultLocation.lng], 13);

            // Añadir las capas de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Añadir un marcador arrastrable en la ubicación predeterminada
            marker = L.marker([defaultLocation.lat, defaultLocation.lng], { draggable: true }).addTo(map);

            // Al mover el marcador, actualizar los campos de latitud y longitud
            marker.on('dragend', function (event) {
                const latLng = marker.getLatLng();
                document.getElementById('ubicacion_lat').value = latLng.lat;
                document.getElementById('ubicacion_lng').value = latLng.lng;
            });
        }

        // Función para buscar dirección y mover el mapa
        function buscarDireccion() {
            const direccion = document.getElementById('search-input').value;
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = data[0].lat;
                        const lon = data[0].lon;
                        // Centrar el mapa en la ubicación encontrada
                        map.setView([lat, lon], 13);
                        marker.setLatLng([lat, lon]);

                        // Actualizar los campos ocultos con la nueva latitud y longitud
                        document.getElementById('ubicacion_lat').value = lat;
                        document.getElementById('ubicacion_lng').value = lon;

                        // Guardar la dirección en la variable 'ubicacion'
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
            <form action="procesar_carga.php" class="content" method="POST">
                <div class="form-group">
                    <label for="titulo_ticket">Título del Ticket:</label>
                    <input type="text" class="form-control" name="titulo_ticket" placeholder="Cargue el título (Obligatorio)" required>
                </div>

                <div class="form-group">
                    <label for="descripcion_ticket">Descripción del Ticket:</label>
                    <textarea class="form-control" name="descripcion_ticket" placeholder="Cargue la descripción (Obligatorio)" required></textarea>
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
                    <label for="id_estado_ticket">Estado:</label>
                    <select class="form-control" name="id_estado_ticket" required>
                    <?php while ($row = $result_estados->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_estado']; ?>"><?php echo $row['nombre_estado']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Toogle para cargar ubicación -->
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="cargar_ubicacion" onclick="toggleUbicacionField()">
                    <label class="form-check-label" for="cargar_ubicacion">Cargar ubicación</label>
                </div>

                <!-- Campo de ubicación -->
                <div class="form-group" id="ubicacion_ticket" style="display: none;">
                    <input type="text" name="search-input" id="search-input" placeholder="Buscar dirección...">
                    <button type="button" onclick="buscarDireccion()">Buscar</button>

                    <div id="map"></div> <!-- Mapa -->
                    <input type="hidden" name="ubicacion_lat" id="ubicacion_lat">
                    <input type="hidden" name="ubicacion_lng" id="ubicacion_lng">
                    <input type="hidden" name="search-input" id="ubicacion"> <!-- Variable para guardar la dirección -->
                </div>
                
                <button type="submit" class="btn-azul">Cargar Ticket</button>
                <a href="../Panel/usuarioPanel.php" class="btn-morado">Volver</a>
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
