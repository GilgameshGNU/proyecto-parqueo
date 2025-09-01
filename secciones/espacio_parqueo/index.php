<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de control</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="back">
    <a href="../vehiculo/index.php" style="text-decoration:none; color:inherit;">Back</a>
  </div>
  <div class="container">
    <h1>Panel de control</h1>

    <div class="plantas">
      <div class="planta">
        <div class="titulo">Planta alta</div>
        <div class="grid" id="planta-alta"></div>
      </div>
      <div class="planta">
        <div class="titulo">Planta baja</div>
        <div class="grid" id="planta-baja"></div>
      </div>
    </div>

    <button class="btn" id="btnAsignarEspacio">Asignar Espacio</button>
  </div>
  <script src="js/script.js"></script>
  <script src="../js/auto-cleanup.js"></script>
</body>
</html>