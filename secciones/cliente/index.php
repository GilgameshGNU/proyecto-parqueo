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
    <div class="back">Back</div>
    <h1>Datos</h1>
    <form action="../vehiculo/index.php" method="get">
      <label for="nombre">Nombre Completo</label>
      <input type="text" id="nombre" name="nombre">

      <label for="telefono">Telefono</label>
      <input type="text" id="telefono" name="telefono">

      <label for="ci">CI</label>
      <input type="text" id="ci" name="ci">

      <button type="submit" class="btn">Siguiente</button>
    </form>
  </div>
</body>

</html>