<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
include("../../db.php");

$id = $_GET['id'] ?? null;
if (!$id) die("ID inválido");

$query = "SELECT * FROM Membresia WHERE IdMembresia=$id";
$result = mysqli_query($conectador, $query);
$membresia = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Membresía</title>
    <link rel="stylesheet" href="tarifas.css">
</head>
<body>
<div class="container form-container">
    <h1>Editar Membresía</h1>
    <form action="procesar_membresia.php" method="POST">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id" value="<?= $membresia['IdMembresia'] ?>">

        <label>Nombre</label>
        <input type="text" value="<?= $membresia['Nombre'] ?>" readonly>

        <label for="precio">Precio (Bs.)</label>
        <input type="number" step="0.01" id="precio" name="precio" value="<?= $membresia['Precio'] ?>">

        <label for="descuento">Descuento (%)</label>
        <input type="number" step="0.01" id="descuento" name="descuento" value="<?= $membresia['Descuento'] ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="Activa" <?= $membresia['Estado']=='Activa'?'selected':'' ?>>Activa</option>
            <option value="Inactiva" <?= $membresia['Estado']=='Inactiva'?'selected':'' ?>>Inactiva</option>
        </select>

        <div class="actions">
            <button type="submit" class="btn-save">Guardar cambios</button>
            <a href="membresias.php" class="btn-back">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
