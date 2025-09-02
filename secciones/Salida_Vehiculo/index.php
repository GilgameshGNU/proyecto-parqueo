<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
// Conexión a la base de datos
include '../../db.php';
// Consulta para obtener vehículos estacionados (tickets abiertos)
$sql = "SELECT 
    t.IdTicket,
    v.IdVehiculo, 
    v.Placa, 
    COALESCE(v.Modelo, 'Sin modelo') AS Modelo,
    COALESCE(v.Marca, 'Sin marca') AS Marca,
    v.Color,
    tv.Nombre AS TipoVehiculo,
    t.FechaHoraEntrada,
    ep.Zona,
    c.IdCliente,
    COALESCE(m.Descuento, 0) AS Descuento,
    ta.Precio
    FROM Ticket t
    INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
    INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
    INNER JOIN TipoVehiculo tv ON v.IdTipo = tv.IdTipo
    INNER JOIN EspacioParqueo ep ON t.IdEspacioParqueo = ep.IdEspacioParqueo
    LEFT JOIN Cliente c ON cv.IdCliente = c.IdCliente
    LEFT JOIN Membresia m ON c.IdMembresia = m.IdMembresia
    LEFT JOIN Tarifa ta on ta.IdTipo = v.IdTipo 
    WHERE t.Estado = 'Abierto'";
$result = $conectador->query($sql);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Parqueo - Salida de Vehículos</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id = "Menu">
        <button id="Back">
            Back
        </button>
    </div>
    <div class="container">
        <h2>Registro de Salida de Vehículos</h2>

        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Buscar por placa, modelo o zona...">
            <button id="searchBtn">Buscar</button>
        </div>

        <div class="vehiculos-lista">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $id_ticket = htmlspecialchars($row['IdTicket']);
                    $placa = htmlspecialchars($row['Placa']);
                    $modelo = htmlspecialchars($row['Modelo']);
                    $marca = htmlspecialchars($row['Marca']);
                    $color = htmlspecialchars($row['Color']);
                    $tipo = htmlspecialchars($row['TipoVehiculo']);
                    $hora_entrada = htmlspecialchars($row['FechaHoraEntrada']);
                    $zona = htmlspecialchars($row['Zona']);
                    $descuento = htmlspecialchars($row['Descuento'] ?? 0);
                    $tarifa = htmlspecialchars($row['Precio']);
                    ?>
                    <div class="vehiculo-item" data-idticket="<?= $id_ticket ?>" data-placa="<?= $placa ?>"
                        data-marca="<?= $marca ?>" data-modelo="<?= $modelo ?>" data-color="<?= $color ?>"
                        data-tipo="<?= $tipo ?>"data-tarifa="<?=$tarifa ?>" data-horaentrada="<?= $hora_entrada ?>" data-zona="<?= $zona ?>"
                        data-descuento="<?= $descuento ?>">
                        <div class="vehiculo-info">
                            <span class="vehiculo-placa"><?= $placa ?></span>
                            <span class="vehiculo-desc"><?= $marca ?>         <?= $modelo ?> - <?= $color ?></span>
                            <span class="vehiculo-tipo"><?= $tipo ?></span>
                        </div>
                        <div class="vehiculo-detalles">
                            <span class="vehiculo-hora">Entrada: <?= date('H:i', strtotime($hora_entrada)) ?></span>
                            <span class="vehiculo-zona">Zona: <?= $zona ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-vehiculos">No hay vehículos estacionados</div>
            <?php endif; ?>
        </div>

        <div class="info-salida" id="infoSalida" style="display: none;">
            <h3>Información de Salida</h3>

            <div class="info-row">
                <label>Hora de salida:</label>
                <input type="text" id="horaSalida" readonly>
            </div>

            <div class="info-row">
                <label>Descuento:</label>
                <input type="text" id="descuento" readonly>
            </div>

            <div class="info-row">
                <label>Tiempo Estacionado:</label>
                <input type="text" id="tiempoEstacionado" readonly>
            </div>

            <div class="info-row">
                <label>Costo Total:</label>
                <input type="text" id="costoTotal" readonly>
            </div>

            <button id="confirmarBtn">Siguiente</button>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>
<?php $conectador->close(); ?>