<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
require_once "detalle.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <div class="back">
      <a href="../espacio_parqueo/index.php" style="text-decoration:none; color:inherit;">Back</a>
    </div>
    <h1>Ticket</h1>

    <div class="ticket-box">
      <h2>Información del Ticket</h2>
      <p><strong>Nombre del cliente:</strong> <?php echo htmlspecialchars($cliente['NombreCompleto']); ?></p>
      <p><strong>CI:</strong> <?php echo htmlspecialchars($cliente['Ci']); ?></p>
      <p><strong>Vehículo:</strong> <?php echo htmlspecialchars($vehiculo['Tipo']); ?> (<?php echo htmlspecialchars($vehiculo['Marca']); ?> <?php echo htmlspecialchars($vehiculo['Modelo']); ?>)</p>
      <p><strong>Placa:</strong> <?php echo htmlspecialchars($vehiculo['Placa']); ?></p>
      <p><strong>Espacio asignado:</strong> <?php echo htmlspecialchars($numeroEspacio); ?></p>
      <p><strong>Fecha de entrada:</strong> <?php echo date('d/m/Y H:i'); ?></p>
      <p><strong>Monto a pagar:</strong> <?php echo number_format($monto_final, 2); ?> Bs</p>
      <?php if ($descuento > 0): ?>
        <p><strong>Descuento aplicado:</strong> <?php echo number_format($descuento, 2); ?>% (Membresía)</p>
      <?php endif; ?>
    </div>

    <a href="crear.php" target="_blank">
      <button class="btn">Imprimir</button>
    </a>

    <a href="../../index.php">
      <button class="btn">Volver al menú</button>
    </a>
  </div>
</body>
</html>