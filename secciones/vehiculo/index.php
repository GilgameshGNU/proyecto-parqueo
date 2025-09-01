<?php
session_start();
// Recuperar datos temporales si existen
$vehiculo_temp = $_SESSION['vehiculo_temp'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datos de vehiculo</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="wrapper">
    <a href="../cliente/index.php" class="back-link">Back</a>
    <div class="container">
      <div class="logo">
        <img src="img/logo.png" alt="Logo Parqueo">
      </div>

      <h1>Datos de vehiculo</h1>

      <?php if (isset($_SESSION['error_vehiculo'])): ?>
        <div style="color: red; margin-bottom: 20px; padding: 10px; background: #ffebee; border-radius: 4px;">
          <?php echo $_SESSION['error_vehiculo'];
          unset($_SESSION['error_vehiculo']); ?>
        </div>
      <?php endif; ?>

      <form action="crear.php" method="post" id="formVehiculo">
        <label for="placa">Placa*</label>
        <input type="text" id="placa" name="placa" required maxlength="15"
          value="<?php echo htmlspecialchars($vehiculo_temp['placa'] ?? ''); ?>">

        <label for="modelo">Modelo</label>
        <input type="text" id="modelo" name="modelo" maxlength="30"
          value="<?php echo htmlspecialchars($vehiculo_temp['modelo'] ?? ''); ?>">

        <label for="marca">Marca</label>
        <input type="text" id="marca" name="marca" maxlength="30"
          value="<?php echo htmlspecialchars($vehiculo_temp['marca'] ?? ''); ?>">

        <label for="color">Color</label>
        <input type="text" id="color" name="color" maxlength="30"
          value="<?php echo htmlspecialchars($vehiculo_temp['color'] ?? ''); ?>">

        <label for="tipo_vehiculo">Tipo de vehículo*</label>
        <select id="tipo_vehiculo" name="tipo_vehiculo" required>
          <option value="">Seleccione un tipo</option>
          <?php
          require_once "../../db.php";
          $query_tipos = mysqli_query($conectador, "SELECT IdTipo, Nombre FROM TipoVehiculo");
          while ($tipo = mysqli_fetch_assoc($query_tipos)) {
            $selected = ($tipo['IdTipo'] == ($vehiculo_temp['tipo_vehiculo'] ?? '')) ? 'selected' : '';
            echo "<option value='{$tipo['IdTipo']}' $selected>{$tipo['Nombre']}</option>";
          }
          ?>
        </select>

        <label for="membresia">Membresía</label>
        <select id="membresia" name="membresia">
          <option value="">Sin membresía</option>
          <?php
          require_once "../../db.php";
          $query_membresias = mysqli_query($conectador, "SELECT IdMembresia, Nombre, Descuento FROM Membresia");
          while ($membresia = mysqli_fetch_assoc($query_membresias)) {
            $selected = ($membresia['IdMembresia'] == ($vehiculo_temp['membresia'] ?? '')) ? 'selected' : '';
            $texto = $membresia['Nombre'] . " " . $membresia['Descuento'] . "%";
            echo "<option value='{$membresia['IdMembresia']}' $selected>$texto</option>";
          }
          ?>
        </select>

        <button type="submit" class="btn">Siguiente</button>
      </form>
    </div>
    <script src="js/script.js"></script>
    <script src="../js/auto-cleanup.js"></script>

</body>

</html>