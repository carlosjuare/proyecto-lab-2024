<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['ID_usuario'])) {
    // Si no ha iniciado sesión, redirige al formulario de inicio de sesión
    header("Location: ./login.html");
    exit();
}

include('../DataBaseConexion/db.php');
$id_usuario = $_SESSION['ID_usuario'];
$query = "SELECT nombre_usuario FROM usuario WHERE ID_usuario = $id_usuario";
$resultado = mysqli_query($DB_conn, $query);

// Verificar si la consulta se ejecutó correctamente
if (!$resultado) {
    // Mostrar mensaje de error si la consulta falla
    echo "Error en la consulta: " . mysqli_error($DB_conn);
    exit();
}

// Verificar si se obtuvo un resultado
if (mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    $nombre_usuario = $fila['nombre_usuario'];
} else {
    $nombre_usuario = "Usuario Desconocido";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Okaro</title>
    <link rel="icon" href="../img/Okaro.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../Estilos/botones.css" />
    <link rel="stylesheet" href="../Estilos/navfoot.css" />
    <link rel="stylesheet" href="../Estilos/estilo.css" />
</head>

<body>
    <nav class="navbar">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </nav>

    <div id="container-central" class="container-fluid">
        <div class="row">
            <div id="container-aside-panel" class="col-12 col-md-4">
                <form action="../Ticket/formulario-ticket-admin.php" method="post">
                    <button type="submit" class="btn-morado btn-ancho">
                        Cargar Ticket
                    </button>
                </form>

                <form action="../Ticket/mostrar_tabla_admin.php" method="post">
                    <button type="submit" class="btn-morado btn-ancho">
                        Gestión de Tickets
                    </button>
                </form>

                <form action="../AdministrarAreas/mostrar-areas.php" method="post">
                    <button type="submit" class="btn-morado btn-ancho">
                        Administar areas
                    </button>
                </form>

                <form action="../AdministrarUsers/mostrar-user.php" method="post">
                    <button type="submit" class="btn-morado btn-ancho">
                        Administrar usuarios
                    </button>
                </form>

                <form action="../Estadisticas/torta.php" method="post">
                    <button type="submit" class="btn-morado btn-ancho">
                        TicketsPorEstado
                    </button>
                </form>

                <form action="../DataBaseConexion/cerrarsesion.php" method="post">
                    <button type="submit" class="btn-morado btn-ancho">
                        Cerrar sesión
                    </button>
                </form>

            </div>

            <div id="container-central-panel" class="col-12 col-md-8">
                <?php
                //Lee el nombre del usuario logueado
                echo "<h2>Bienvenido, $nombre_usuario</h2>";
                ?>
                <p>Seleccione una opción en el menú lateral.</p>

                <!-- Contenedor donde se mostrará la cantidad de tickets -->
                <div id="cantidad-tickets">
                    <!-- Aquí se mostrará el contenido de preguntas.php automáticamente -->
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>Derechos Reservados ©</p>
    </footer>
    
    <script>
        // Función para cargar la cantidad de tickets automáticamente
        function cargarCantidadTickets() {
            // Mostramos un mensaje de carga antes de hacer la solicitud
            document.getElementById('cantidad-tickets').innerHTML = "<p>Cargando cantidad de tickets...</p>";

            // Usamos fetch para hacer la solicitud a la página PHP
            fetch('../Estadisticas/preguntas.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar el contenido: ' + response.statusText);
                    }
                    return response.text(); // Convertimos la respuesta a texto
                })
                .then(data => {
                    // Insertamos el contenido en el contenedor
                    document.getElementById('cantidad-tickets').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error en la solicitud:', error);
                    document.getElementById('cantidad-tickets').innerHTML = "<p>Error al cargar la cantidad de tickets.</p>";
                });
        }

        // Cargar la cantidad de tickets al cargar la página
        window.onload = cargarCantidadTickets;
    </script>
</body>

</html>
