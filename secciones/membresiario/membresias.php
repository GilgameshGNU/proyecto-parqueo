<?php
include("../../db.php");

$query = "SELECT * FROM Membresia";
$result = mysqli_query($conectador, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Membresías</title>
    <link rel="stylesheet" href="tarifas.css">
</head>
<body>
<div class="container">
    <h1>Listado de Membresías</h1>
    <a href="nueva_membresia.php" class="btn-save" style="margin-bottom: 15px; display:inline-block;">+ Nueva Membresía</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio (Bs.)</th>
                <th>Descuento (%)</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['IdMembresia'] ?></td>
                <td><?= $row['Nombre'] ?></td>
                <td><?= number_format($row['Precio'], 2) ?></td>
                <td><?= $row['Descuento'] ?></td>
                <td><?= $row['Estado'] ?></td>
                <td>
                    <a href="editar_membresia.php?id=<?= $row['IdMembresia'] ?>" class="btn-edit">Editar</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
