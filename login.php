
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Parqueo</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="logo">
    <img src="secciones/vehiculo/img/logo.png" alt="Logo Parqueo" width="120">
  </div>
  <div class="container">
    <div class="back"><a href="index.php">Back</a></div>
    <h1>Iniciar Sesión</h1>
    <form action="procesar_login.php" method="POST">
      <div class="input-group">
        <label for="nombre">Usuario</label>
        <input type="text" id="nombre" name="nombre" required>
      </div>
      <div class="input-group">
        <label for="contra">Contraseña</label>
        <input type="password" id="contra" name="contra" required>
      </div>
      <button type="submit" class="btn">Ingresar</button>
      <?php if(isset($_GET['error'])): ?>
        <div class="input-group" style="color:red;">Usuario o contraseña incorrectos</div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
