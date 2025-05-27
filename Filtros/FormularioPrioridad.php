<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Tickets por Prioridad</title>
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
        <h2 class="mb-4 text-center">Consulta de Tickets por Prioridad</h2>

<form action="FiltroPrioridad.php" method="post">
    <label for="prioridad">Selecciona una prioridad:</label>
    <select name="prioridad" id="prioridad" required>
        <?php
        // Incluir el archivo de conexión a la base de datos
        include_once '../DataBaseConexion/db.php';

        // Verificar si la conexión está establecida correctamente
        if ($DB_conn) {
            // Consultar las prioridades
            $query = "SELECT id_prioridad, nombre_prioridad FROM prioridades";
            $result = mysqli_query($DB_conn, $query);

            // Generar las opciones del desplegable con los resultados
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id_prioridad'] . '">' . $row['nombre_prioridad'] . '</option>';
            }

            // Liberar el conjunto de resultados
            mysqli_free_result($result);

            // Cerrar la conexión a la base de datos
            mysqli_close($DB_conn);
        }
        ?>
    </select>

    <button type="submit" class="btn-azul">Continuar</button>
</form>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">Derechos Reservados ©</p>
        </div>
    </footer>
</body>
</html>
