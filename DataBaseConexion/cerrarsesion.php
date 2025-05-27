<?php

// Inicia la sesión
session_start();

// Destruye la sesión actual
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header('Location: ../Login/login.html');
exit();

?>