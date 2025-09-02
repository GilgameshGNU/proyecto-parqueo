<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
include(__DIR__ . "/../../db.php");

$id = $_GET['id'];

$sql = "SELECT t.IdTicket, t.FechaHoraEntrada, c.NombreCompleto, v.Placa, v.Modelo, v.Marca, v.Color, tv.Nombre AS TipoVehiculo, e.NumeroEspacio, e.Zona
        FROM Ticket t
        INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
        INNER JOIN Cliente c ON cv.IdCliente = c.IdCliente
        INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
        INNER JOIN TipoVehiculo tv ON v.IdTipo = tv.IdTipo
        INNER JOIN EspacioParqueo e ON t.IdEspacioParqueo = e.IdEspacioParqueo
        WHERE t.IdTicket = $id";
$result = mysqli_query($conectador, $sql);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Vehículo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Detalle del Vehículo</h2>
        <p><b>Cliente:</b> <?php echo $data['NombreCompleto']; ?></p>
        <p><b>Placa:</b> <?php echo $data['Placa']; ?></p>
        <p><b>Modelo:</b> <?php echo $data['Modelo']; ?></p>
        <p><b>Marca:</b> <?php echo $data['Marca']; ?></p>
        <p><b>Color:</b> <?php echo $data['Color']; ?></p>
        <p><b>Tipo de Vehículo:</b> <?php echo $data['TipoVehiculo']; ?></p>
        <p><b>Zona:</b> <?php echo $data['Zona']; ?></p>
        <p><b>Espacio:</b> <?php echo $data['NumeroEspacio']; ?></p>
        <p><b>Hora de entrada:</b> <?php echo $data['FechaHoraEntrada']; ?></p>

        <a href="menu.php" class="btn-ver">Volver</a>
    </div>
</body>
</html>
