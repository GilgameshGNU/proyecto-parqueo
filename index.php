<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
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
      <a href="secciones//index.php" class="btn">Vehículo Dentro</a>
      <a href="secciones//index.php" class="btn">Tarifa</a>
      <a href="secciones//index.php" class="btn">Membresía</a>
    </div>
  </div>
</body>

</html>