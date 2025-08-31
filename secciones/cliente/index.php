<?php
session_start();
// Recuperar datos temporales si existen
$cliente_temp = $_SESSION['cliente_temp'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datos</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">
    <div class="back">
      <a href="../../index.php" style="text-decoration:none; color:inherit;">Back</a>
    </div>
    <h1>Datos</h1>

    <?php if (isset($_SESSION['error'])): ?>
      <div style="color: red; margin-bottom: 20px; padding: 10px; background: #ffebee; border-radius: 4px;">
        <?php echo $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form action="crear.php" method="post" id="formCliente">
      <label for="nombre">Nombre Completo*</label>
      <input type="text" id="nombre" name="nombre" required
        value="<?php echo htmlspecialchars($cliente_temp['nombre'] ?? ''); ?>">

      <label for="telefono">Teléfono</label>
      <input type="text" id="telefono" name="telefono"
        value="<?php echo htmlspecialchars($cliente_temp['telefono'] ?? ''); ?>">

      <label for="ci">CI</label>
      <input type="text" id="ci" name="ci"
        value="<?php echo htmlspecialchars($cliente_temp['ci'] ?? ''); ?>">

      <button type="submit" class="btn">Siguiente</button>
    </form>
  </div>
  <script src="js/script.js"></script>
</body>

</html>