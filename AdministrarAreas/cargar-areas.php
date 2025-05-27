<?php
// Incluir el archivo de conexión a la base de datos
include_once '../DataBaseConexion/db.php';
// Verificar si la sesión del usuario está activa
if (!isset($_SESSION['ID_usuario'])) {
    header("Location: ../html/login.html");
    exit(); // Asegurar que el script se detenga después de la redirección
}
// Obtener los datos del formulario
$nombre_area = $_POST['nombre'];
$descripcion_ticket = $_POST['descripcion'];
try {
    // Insertar un nuevo ticket
    $query_insert_area = "INSERT INTO areas (id_area, nombre_area, descripcion_area) 
                            VALUES (NULL, '$nombre_area', '$descripcion_ticket')";
    $DB_conn->query($query_insert_area);
    // Redirigir al usuario a la página de éxito específica
    header("Location: ../AdministrarAreas/AreaExitosa.php");
    exit(); // Asegurar que el script se detenga después de la redirección
} catch (mysqli_sql_exception $exception) {
    echo "Error al crear: " . $exception->getMessage();
}
// Cerrar la conexión (en caso de error)
$DB_conn->close();
?>
