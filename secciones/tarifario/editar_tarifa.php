<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
include("../../db.php");

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID de tarifa no válido");
}

$query = "SELECT t.*, tv.Nombre AS NombreVehiculo 
          FROM Tarifa t
          INNER JOIN TipoVehiculo tv ON t.IdTipo = tv.IdTipo
          WHERE IdTarifa = $id";
$result = mysqli_query($conectador, $query);
$tarifa = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarifa</title>
    <link rel="stylesheet" href="tarifas.css">
</head>
<body>
    <div class="container form-container">
        <h1>Editar Tarifa</h1>
        <form action="procesar_tarifa.php" method="POST">
            <input type="hidden" name="id" value="<?= $tarifa['IdTarifa'] ?>">
            <input type="hidden" name="tipo" value="<?= $tarifa['IdTipo'] ?>">

            <label>Tipo de vehículo</label>
            <input type="text" value="<?= $tarifa['NombreVehiculo'] ?>" readonly>

            <label for="precio">Precio (Bs.)</label>
            <input type="number" step="0.01" id="precio" name="precio" value="<?= $tarifa['Precio'] ?>" required>

            <div class="actions">
                <button type="submit" class="btn-save">Guardar cambios</button>
                <a href="tarifas.php" class="btn-back">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>
