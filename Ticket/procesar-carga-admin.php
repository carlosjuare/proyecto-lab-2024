<?php
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';

// Iniciar la sesión
session_start();

// Verificar si la sesión del usuario está activa
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../html/login.html");
    exit(); // Asegurar que el script se detenga después de la redirección
}

// Obtener los datos del formulario
$titulo_ticket = $_POST['titulo_ticket'];
$descripcion_ticket = $_POST['descripcion_ticket'];
$id_usuario_ticket = $_SESSION['ID_usuario']; // Usar el ID de usuario de la sesión
$id_area_ticket = $_POST['id_area_ticket']; // Usar el ID de área seleccionado en el formulario
$id_prioridad_ticket = $_POST['id_prioridad_ticket'];
$id_estado_ticket = $_POST['id_estado_ticket'];

// Verificar si se ingresó una ubicación
$latitud = $_POST['ubicacion_lat'];
$longitud = $_POST['ubicacion_lng'];
$direccion = $_POST['search-input']; // Campo de búsqueda de la dirección

try {
    // Iniciar la transacción
    $DB_conn->begin_transaction();

    // Insertar la ubicación si se ingresó
    if (!empty($latitud) && !empty($longitud)) {
        $query_insert_ubicacion = "INSERT INTO ubicaciones (direccion, latitud, longitud) 
                                   VALUES ('$direccion', '$latitud', '$longitud')";
        $DB_conn->query($query_insert_ubicacion);

        // Obtener el ID de la ubicación recién insertada
        $id_ubicacion = $DB_conn->insert_id;
    } else {
        $id_ubicacion = "NULL"; // Si no hay ubicación, se guarda como NULL
    }

    // Insertar un nuevo ticket con el ID de ubicación
    $query_insert_ticket = "INSERT INTO tickets (titulo_ticket, descripcion_ticket, id_usuario_ticket, id_area_ticket, id_prioridad_ticket, id_estado_ticket, id_ubicacion) 
                            VALUES ('$titulo_ticket', '$descripcion_ticket', $id_usuario_ticket, $id_area_ticket, $id_prioridad_ticket, $id_estado_ticket, $id_ubicacion)";
    $DB_conn->query($query_insert_ticket);

    // Confirmar la transacción
    $DB_conn->commit();

    // Redirigir al usuario a la página de carga exitosa
    header("Location: CargaExitosa.php");
    exit(); // Asegurar que el script se detenga después de la redirección

} catch (mysqli_sql_exception $exception) {
    // En caso de error, revertir la transacción
    $DB_conn->rollback();
    echo "Error al crear el ticket: " . $exception->getMessage();
}

// Cerrar la conexión
$DB_conn->close();
?>
