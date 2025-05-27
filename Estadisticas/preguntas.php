<?php
session_start();
include("../DataBaseConexion/db.php");

// Consulta para obtener el total de tickets
$sql_total = "SELECT COUNT(*) AS total_tickets FROM tickets";
$result_total = $DB_conn->query($sql_total);

// Inicializar variables
$total_tickets = 0;
$tickets_area_1 = 0; // Tickets del área 1
$tickets_area_2 = 0; // Tickets del área 2
$tickets_area_3 = 0; // Tickets del área 3

// Verificar si se obtuvieron resultados para los tickets totales
if ($result_total->num_rows > 0) {
    $row_total = $result_total->fetch_assoc();
    $total_tickets = $row_total['total_tickets'];
}

// Consulta para contar los tickets del área 1
$sql_area_1 = "SELECT COUNT(*) AS tickets_area_1 FROM tickets WHERE id_area_ticket = 1";
$result_area_1 = $DB_conn->query($sql_area_1);

// Verificar si se obtuvieron resultados para el área 1
if ($result_area_1->num_rows > 0) {
    $row_area_1 = $result_area_1->fetch_assoc();
    $tickets_area_1 = $row_area_1['tickets_area_1'];
}

// Consulta para contar los tickets del área 2
$sql_area_2 = "SELECT COUNT(*) AS tickets_area_2 FROM tickets WHERE id_area_ticket = 2";
$result_area_2 = $DB_conn->query($sql_area_2);

// Verificar si se obtuvieron resultados para el área 2
if ($result_area_2->num_rows > 0) {
    $row_area_2 = $result_area_2->fetch_assoc();
    $tickets_area_2 = $row_area_2['tickets_area_2'];
}

// Consulta para contar los tickets del área 3
$sql_area_3 = "SELECT COUNT(*) AS tickets_area_3 FROM tickets WHERE id_area_ticket = 3";
$result_area_3 = $DB_conn->query($sql_area_3);

// Verificar si se obtuvieron resultados para el área 3
if ($result_area_3->num_rows > 0) {
    $row_area_3 = $result_area_3->fetch_assoc();
    $tickets_area_3 = $row_area_3['tickets_area_3'];
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
    <link rel="stylesheet" href="../AdminLTE/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../AdminLTE/dist/css/adminlte.min.css.map">

    <!-- jQuery -->
    <script src="../AdminLTE/dist/js/adminlte.min.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <!-- Info boxes -->
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-ticket-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tickets Totales</span>
                                    <span class="info-box-number">
                                        <?php echo $total_tickets; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-ticket-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tickets A.P.</span>
                                    <span class="info-box-number">
                                        <?php echo $tickets_area_1; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ticket-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tickets de Soporte</span>
                                    <span class="info-box-number">
                                        <?php echo $tickets_area_2; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-ticket-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tickets de Almacen</span>
                                    <span class="info-box-number">
                                        <?php echo $tickets_area_3; ?>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <!-- Añadir más boxes de estadísticas si es necesario -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
                
            </section>
        </div>
    </div>
</body>
</html>
