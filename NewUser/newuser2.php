<?php
include('../DataBaseConexion/db.php');

// Check connection


$sql = "SELECT id_area, nombre_area FROM areas";
$result = $DB_conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulario de Usuario</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../Estilos/botones.css"/>
  <link rel="stylesheet" href="../Estilos/navfoot.css"/>
  <link rel="stylesheet" href="../Estilos/estilo.css"/>
</head>

<body style="background-color: #f8f9fa">
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #310467">
    <div class="container">
      <!-- Texto del logo que dirige a otro archivo HTML -->
      <a class="navbar-brand" href="#">
        <img src="../img/Okaro_White.png" alt="Logo" class="img-fluid" height="100%" width="100%" />
      </a>
    </div>
  </nav>

  <div class="container mt-5 content">
    <h2 class="mb-4">Agregar Usuario</h2>

    <form action="./newuser.php" method="post">
      <div class="form-group">
        <label for="nombre_usuario">Nombre:</label>
        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required />
      </div>

      <div class="form-group">
        <label for="apellido_usuario">Apellido:</label>
        <input type="text" class="form-control" id="apellido_usuario" name="apellido_usuario" required />
      </div>

      <div class="form-group">
        <label for="email_usuario">Email:</label>
        <input type="email" class="form-control" id="email_usuario" name="email_usuario" required />
      </div>

      <div class="form-group">
        <label for="telefono_usuario">Teléfono:</label>
        <input type="tel" class="form-control" id="telefono_usuario" name="telefono_usuario" required />
      </div>

      <div class="form-group">
        <label for="clave_usuario">Contraseña:</label>
        <input type="password" class="form-control" id="clave_usuario" name="clave_usuario" required />
      </div>

      <!-- Menú desplegable para seleccionar el área -->
      <div class="form-group">
        <label for="area_usuario">Área:</label>
        <select class="form-control" id="area_usuario" name="area_usuario">
          <?php
         
          if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<option value='" . $row['id_area'] . "'>" . $row['nombre_area'] . "</option>";
            }
          }
          ?>
        </select>
      </div>

      <button type="submit" class="btn-azul"> Agregar Usuario </button>
      <button class="btn-morado" onclick="window.location.href='../Login/login.html';"> Volver</button>
    </form>
  </div>

  <!-- Agregamos un elemento div para dar espacio al final del contenido principal -->
  <div class="spacer"></div>

  <!-- Footer -->
  <footer>
    <p>Derechos Reservados ©</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

