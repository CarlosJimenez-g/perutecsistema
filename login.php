<?php
    include("backend/login_backend.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PERUTEC</title>
  <link rel="icon" type="image/png" href="imagenes/logo_perutec.png">
  <link rel="stylesheet" href="css/estilologin.css" />
</head>

<body>
  <div class="login-container">
    <img src="imagenes/logo_perutec_letrablanca.png" alt="Logo Perutec">
    <form name="login1" action="" method="post">
        <h1>Iniciar Sesión</h1>

        <input name="nombre" type="text" placeholder="Ingrese tu nombre" required>
        <input type="password" name="contrasena" placeholder="Ingrese tu contraseña" required>

        <div class="inicio-sesion">
          <button type="submit" name="ingresar" value="Aceptar">Iniciar sesión</button>
        </div>
    </form>
  </div>
</body>
</html>
