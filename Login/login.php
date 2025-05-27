<?php
session_start();
include("../DataBaseConexion/db.php");

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si los campos 'email_usuario' y 'clave_usuario' están definidos en $_POST
    if (isset($_POST['email_usuario'], $_POST['clave_usuario'])) {
        $email_usuario = $_POST['email_usuario'];
        $clave_usuario = $_POST['clave_usuario'];

        // Consulta para buscar al usuario por nombre de usuario y contraseña (usando consulta preparada)
        $query = "SELECT ID_usuario, tipo_usuario, id_area_usuario FROM usuario WHERE email_usuario = ? AND clave_usuario = ?";
        
        // Preparar la consulta
        $stmt = $DB_conn->prepare($query);
        if ($stmt === false) {
            exit("Error al preparar la consulta SQL: " . $DB_conn->error);
        }

        // Bind parameters y ejecutar la consulta
        $stmt->bind_param("ss", $email_usuario, $clave_usuario);
        $stmt->execute();

        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_usuario = $row['ID_usuario'];
            $tipo_usuario = $row['tipo_usuario'];
            $id_area_usuario = $row['id_area_usuario'];

            // Almacenar el ID del usuario y el ID del área en la sesión
            $_SESSION['ID_usuario'] = $id_usuario;
            $_SESSION['id_area_usuario'] = $id_area_usuario;

            // Redirigir a la página correspondiente según el tipo de usuario
            if ($tipo_usuario === 'administrador') {
                header('Location: ../Panel/administradorPanel.php');
            } elseif ($tipo_usuario === 'usuario') {
                header('Location: ../Panel/usuarioPanel.php');
            } else {
                // Tipo de usuario no reconocido
                echo "Tipo de usuario no reconocido.";
            }
            exit();
        } else {
            echo "Usuario no encontrado o credenciales incorrectas.";
            // Permanecer en la misma página y mostrar el mensaje de error
        }
    } else {
        echo "Error: 'email_usuario' o 'clave_usuario' no están definidos en el formulario.";
    }
}

// Cerrar la conexión al finalizar
$DB_conn->close();
?>