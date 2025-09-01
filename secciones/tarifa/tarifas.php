<?php
include("../../db.php");

$query = "SELECT t.IdTarifa, tv.Nombre AS TipoVehiculo, t.Precio, t.FechaInicio, t.FechaFin
          FROM Tarifa t
          INNER JOIN TipoVehiculo tv ON t.IdTipo = tv.IdTipo";
$result = mysqli_query($conectador, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Tarifas</title>
    <link rel="stylesheet" href="tarifas.css">
</head>
<body>
    <div class="container">
        <h1>Listado de Tarifas</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo Vehículo</th>
                    <th>Precio (Bs.)</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['IdTarifa'] ?></td>
                    <td><?= $row['TipoVehiculo'] ?></td>
                    <td><?= number_format($row['Precio'], 2) ?></td>
                    <td><?= $row['FechaInicio'] ?></td>
                    <td><?= $row['FechaFin'] ?? '-' ?></td>
                    <td><a href="editar_tarifa.php?id=<?= $row['IdTarifa'] ?>" class="btn-edit">Editar</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
