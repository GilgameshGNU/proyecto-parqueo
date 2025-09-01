<?php
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
    ta.Precio AS TarifaHora
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
                    $tarifa_hora = htmlspecialchars($row['TarifaHora']);
                    
                    // Calcular costo total basado en el tiempo transcurrido
                    $hora_entrada_obj = new DateTime($hora_entrada);
                    $hora_actual = new DateTime();
                    $diferencia = $hora_entrada_obj->diff($hora_actual);
                    $horas_transcurridas = $diferencia->h + ($diferencia->days * 24);
                    $minutos_transcurridos = $diferencia->i;
                    
                    // Si hay minutos, se cobra una hora adicional (política del parqueo)
                    $horas_cobrar = $horas_transcurridas + ($minutos_transcurridos > 0 ? 1 : 0);
                    $costo_sin_descuento = $horas_cobrar * $tarifa_hora;
                    
                    // Aplicar descuento de membresía si existe
                    $costo_total = $costo_sin_descuento;
                    if ($descuento > 0) {
                        $costo_total = $costo_sin_descuento * (1 - ($descuento / 100));
                    }
                    
                    // Debug: mostrar información del cálculo
                    // echo "<!-- Debug: Tarifa por hora: $tarifa_hora, Horas: $horas_cobrar, Descuento: $descuento%, Costo: $costo_total -->";
                    ?>
                    <div class="vehiculo-item" data-idticket="<?= $id_ticket ?>" data-placa="<?= $placa ?>"
                        data-marca="<?= $marca ?>" data-modelo="<?= $modelo ?>" data-color="<?= $color ?>"
                        data-tipo="<?= $tipo ?>" data-tarifa="<?=$tarifa_hora ?>" data-horaentrada="<?= $hora_entrada ?>" data-zona="<?= $zona ?>"
                        data-descuento="<?= $descuento ?>" data-costo-total="<?= number_format($costo_total, 2) ?>">
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
            <p style="text-align: center; color: #7f8c8d; font-size: 14px; margin-bottom: 20px;">
                Selecciona un vehículo para ver los detalles y confirmar su salida
            </p>

            <div class="info-row">
                <label>Hora de salida:</label>
                <input type="text" id="horaSalida" readonly>
            </div>

            <div class="info-row">
                <label>Tarifa por Hora:</label>
                <input type="text" id="tarifaHora" readonly>
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

            <button id="confirmarBtn">Confirmar Salida</button>
            <button id="irPagoBtn" style="display: none;">Ir a Pago</button>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>
<?php $conectador->close(); ?>