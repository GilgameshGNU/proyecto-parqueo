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

    <form action="../espacio_parqueo/index.php" method="get">
      <label for="placa">Placa</label>
      <input type="text" id="placa" name="placa">

      <label for="modelo">Modelo</label>
      <input type="text" id="modelo" name="modelo">

      <label for="marca">Marca</label>
      <input type="text" id="marca" name="marca">

      <label for="color">Color</label>
      <input type="text" id="color" name="color">

      <label for="tipo">Tipo de vehiculo</label>
      <select id="tipo" name="tipo">

      </select>

      <label for="membresia">Membresia</label>
      <select id="membresia" name="membresia">

      </select>

      <button type="submit" class="btn">Siguiente</button>
    </form>
  </div>
</body>

</html>