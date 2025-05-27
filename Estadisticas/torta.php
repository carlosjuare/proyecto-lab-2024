<?php
session_start();
include("../DataBaseConexion/db.php");

// Consulta para contar los tickets por estado
$sql_abiertos = "SELECT COUNT(*) AS tickets_abiertos FROM tickets WHERE id_estado_ticket = 1";
$sql_en_proceso = "SELECT COUNT(*) AS tickets_en_proceso FROM tickets WHERE id_estado_ticket = 2";
$sql_cerrados = "SELECT COUNT(*) AS tickets_cerrados FROM tickets WHERE id_estado_ticket = 3";

// Ejecutar las consultas y almacenar los resultados
$result_abiertos = $DB_conn->query($sql_abiertos);
$result_en_proceso = $DB_conn->query($sql_en_proceso);
$result_cerrados = $DB_conn->query($sql_cerrados);

// Inicializar variables
$tickets_abiertos = 0;
$tickets_en_proceso = 0;
$tickets_cerrados = 0;

// Verificar si se obtuvieron resultados para "ABIERTO"
if ($result_abiertos->num_rows > 0) {
    $row_abiertos = $result_abiertos->fetch_assoc();
    $tickets_abiertos = $row_abiertos['tickets_abiertos'];
}

// Verificar si se obtuvieron resultados para "EN PROCESO"
if ($result_en_proceso->num_rows > 0) {
    $row_en_proceso = $result_en_proceso->fetch_assoc();
    $tickets_en_proceso = $row_en_proceso['tickets_en_proceso'];
}

// Verificar si se obtuvieron resultados para "CERRADO"
if ($result_cerrados->num_rows > 0) {
    $row_cerrados = $result_cerrados->fetch_assoc();
    $tickets_cerrados = $row_cerrados['tickets_cerrados'];
}

$DB_conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="../Estadisticas/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../Estadisticas/dist/css/adminlte.min.css.map">

    <!-- jQuery -->
    <script src="../Estadisticas/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Okaro</title>
    <link rel="icon" href="../img/Okaro.ico" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../Estilos/botones.css" />
    <link rel="stylesheet" href="../Estilos/navfoot.css" />
    <link rel="stylesheet" href="../Estilos/estilo.css" />

<style>
#estadoTicketsChart 
{
    max-width: 100%; /* El gráfico no excederá el ancho del contenedor */
    height: auto;    /* Altura automática para mantener las proporciones */
    max-height: 400px; /* Limitar la altura máxima del gráfico */
}

</style>

</head>
<body>
    <nav class="navbar">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" />
    </nav>
    <div class="containter-central-graf-torta">
        <div class="col-md-">
            <canvas id="estadoTicketsChart" width="400" height="400"> </canvas>
        </div>
        
        <div class="text-center" style="margin-top: 20px;">
    <a href="../Panel/administradorPanel.php" class="btn-morado">Volver al Panel de Administrador</a>
        </div>

    </div>

    <script>
        // Obtener los datos de PHP
        var ticketsAbiertos = <?php echo $tickets_abiertos; ?>;
        var ticketsEnProceso = <?php echo $tickets_en_proceso; ?>;
        var ticketsCerrados = <?php echo $tickets_cerrados; ?>;

        console.log("Tickets Abiertos:", ticketsAbiertos);
        console.log("Tickets En Proceso:", ticketsEnProceso);
        console.log("Tickets Cerrados:", ticketsCerrados);

        // Crear gráfico
        var ctx = document.getElementById('estadoTicketsChart').getContext('2d');
        var estadoTicketsChart = new Chart(ctx, {
            type: 'pie', 
            data: {
                labels: ['Abiertos', 'En Proceso', 'Cerrados'],
                datasets: [{
                    label: 'Tickets por Estado',
                    data: [ticketsAbiertos, ticketsEnProceso, ticketsCerrados],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)', // Color para 'Abiertos'
                        'rgba(255, 206, 86, 0.2)', // Color para 'En Proceso'
                        'rgba(75, 192, 192, 0.2)'  // Color para 'Cerrados'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Tickets por Estado'
                    }
                }
            }
        });

        console.log("Gráfico creado exitosamente");
    </script>

    <footer>
        <p>Derechos Reservados ©</p>
    </footer>
</body>
</html>
