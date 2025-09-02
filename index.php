<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú Principal</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">
    <!-- Logo -->
    <img src="secciones/vehiculo/img/logo.png" alt="Logo" class="logo">

    <!-- Botones -->
    <!-- Botones -->
    <div class="menu">
      <a href="secciones/cliente/index.php" class="btn">Ingreso de vehículo</a>
      <a href="secciones/Salida_Vehiculo/index.php" class="btn">Salida de vehículo</a>
      <a href="secciones/vehiculario/menu.php" class="btn">Vehículo Dentro</a>
      <a href="secciones/tarifario/tarifas.php" class="btn">Tarifa</a>
      <a href="secciones/membresiario/membresias.php" class="btn">Membresía</a>
      <?php if ($_SESSION['rol'] === 'Administrador'): ?>
        <a href="/proyecto-parqueo/secciones/Report/index.php" class="btn">Reportes</a>
      <?php endif; ?>
      <a href="cerra.php" class="btn">Cerrar sesión</a>
    </div>
  </div>
</body>

</html>