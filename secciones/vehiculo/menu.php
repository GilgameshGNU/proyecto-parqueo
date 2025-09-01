<?php
include(__DIR__ . "/../../db.php");

// consulta para vehículos dentro
$sql = "SELECT 
            v.Placa, v.Modelo, v.Marca, v.Color,
            t.IdTicket,
            tv.Nombre AS TipoVehiculo,
            e.Zona
        FROM Ticket t
        INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
        INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
        INNER JOIN TipoVehiculo tv ON v.IdTipo = tv.IdTipo
        INNER JOIN EspacioParqueo e ON t.IdEspacioParqueo = e.IdEspacioParqueo
        WHERE t.Estado = 'Abierto'";

$result = mysqli_query($conectador, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vehículo dentro</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Botón Volver -->
        <a href="/../../index.php" class="btn-volver">Volver</a>
        <h2>Vehículo dentro</h2>

        <input type="text" id="buscador" placeholder="Buscar...">

        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>Tipo de vehículo</th>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaVehiculos">
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['Placa']; ?></td>
                        <td><?php echo $row['Modelo']; ?></td>
                        <td><?php echo $row['Marca']; ?></td>
                        <td><?php echo $row['Color']; ?></td>
                        <td><?php echo $row['TipoVehiculo']; ?></td>
                        <td><?php echo $row['Zona']; ?></td>
                        <td>
                            <a href="detalle.php?id=<?php echo $row['IdTicket']; ?>" class="btn-ver">👁️</a>
                            <a href="editar.php?id=<?php echo $row['IdTicket']; ?>" class="btn-edit">✏️</a>
                           
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
