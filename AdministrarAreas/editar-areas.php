<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Área</title>
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
        <h2>Editar Área</h2>
        <form action="actualizar-area.php" method="POST">
            <label for="nombre_area">Nombre del Área:</label><br>
            <input type="text" id="nombre_area" name="nombre_area" required><br>
            <label for="descripcion_area">Descripción del Área:</label><br>
            <textarea id="descripcion_area" name="descripcion_area" required></textarea><br><br>
            <input type="hidden" id="id_area" name="id_area" value="<?php echo $_GET['id']; ?>">
            <!-- Aquí agregamos un campo oculto para el ID del área -->

            <input type="submit" value="Actualizar" class="btn-azul">
            <button onclick="window.location.href='../AdministrarAreas/mostrar-areas.php'" class="btn-morado">Volver</button>
        </form>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">Derechos Reservados ©</p>
        </div>
    </footer>
</body>
</html>

