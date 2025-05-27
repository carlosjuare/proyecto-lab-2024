<?php
// Llamada a la API del clima (código existente)
$status = "";
$msg = "";
$city = "";
$apiKey = "63af21a9878dc489df90babb908c27dc";
if (isset($_POST['submit'])) {
    $city = $_POST['city'];
    $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, true);
    if ($result['cod'] == 200) {
        $status = "yes";
    } else {
        $msg = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta del Clima</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Estilos/botones.css">
    <link rel="stylesheet" href="../Estilos/navfoot.css">
    <style>
        /* Estilo general */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 0;
        }

        /* Barra de navegación */
        .navbar {
            background-color: #310467;
            padding: 15px;
            text-align: center;
        }

        .navbar img {
            height: 50px;
        }

        /* Contenedor principal */
        .container-central {
            padding: 40px 20px;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            color: #310467;
        }

        /* Botón de consulta */
        .btn-primary {
            background-color: #310467;
            border: none;
        }

        .btn-primary:hover {
            background-color: #41277a;
        }

        /* Estilo del widget del clima */
        .widget {
            margin-top: 30px;
        }

        .card {
            border-radius: 10px;
            border: none;
        }

        .weatherIcon {
            padding: 20px;
        }

        .weatherInfo {
            background-color: #343a40;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .temperature {
            font-size: 60px;
            color: #fff;
        }

        .description {
            color: #fff;
            text-align: center;
        }

        .weatherCondition {
            font-size: 25px;
        }

        .place {
            font-size: 18px;
            font-weight: lighter;
        }

        .date {
            background-color: #310467;
            color: white;
            padding: 15px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .card-footer {
            font-size: 18px;
        }

        /* Estilo del formulario */
        .form-control {
            border-radius: 10px;
            padding: 15px;
            font-size: 18px;
        }

        .alert-danger {
            border-radius: 10px;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid">
    </nav>

    <!-- Contenedor principal -->
    <div class="container-fluid container-central">
        <h2>Consulta el Clima</h2>
        <form method="post" class="form-group">
            <input type="text" class="form-control mb-3" placeholder="Ingrese el nombre de la ciudad" name="city" value="<?php echo $city ?>" required />
            <button type="submit" class="btn btn-primary btn-block" name="submit">Consultar</button>
            <?php if ($msg): ?>
                <div class="alert alert-danger"><?php echo $msg; ?></div>
            <?php endif; ?>
        </form>

        <!-- Widget del clima -->
        <?php if ($status == "yes") { ?>
            <article class="widget card mt-4">
                <div class="weatherIcon card-header text-center bg-light">
                    <img src="http://openweathermap.org/img/wn/<?php echo $result['weather'][0]['icon'] ?>@4x.png" class="img-fluid" />
                </div>
                <div class="weatherInfo card-body text-center">
                    <div class="temperature">
                        <span><?php echo round($result['main']['temp'] - 273.15) ?>°</span>
                    </div>
                    <div class="description">
                        <div class="weatherCondition"><?php echo $result['weather'][0]['main'] ?></div>
                        <div class="place"><?php echo $result['name'] ?></div>
                    </div>
                </div>
                <div class="date card-footer text-center">
                    <?php echo date('d M', $result['dt']) ?>
                </div>
            </article>
        <?php } ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
