<?php
session_start();
include '../../db.php';

if (isset($_GET['id_pago'])) {
    $id_pago = $_GET['id_pago'];
    
    $sql = "SELECT 
        p.IdPago,
        p.Nit,
        p.RazonSocial,
        p.FechaPago,
        p.MontoTotal,
        p.MetodoPago,
        t.IdTicket,
        t.FechaHoraEntrada,
        t.FechaHoraSalida,
        v.Placa,
        tv.Nombre AS TipoVehiculo,
        ep.NumeroEspacio,
        ep.Zona,
        c.NombreCompleto AS Cliente,
        COALESCE(m.Descuento, 0) AS Descuento,
        TIMESTAMPDIFF(MINUTE, t.FechaHoraEntrada, t.FechaHoraSalida) AS MinutosEstacionado
    FROM Pago p
    INNER JOIN Ticket t ON p.IdTicket = t.IdTicket
    INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
    INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
    INNER JOIN TipoVehiculo tv ON v.IdTipo = tv.IdTipo
    INNER JOIN EspacioParqueo ep ON t.IdEspacioParqueo = ep.IdEspacioParqueo
    LEFT JOIN Cliente c ON cv.IdCliente = c.IdCliente
    LEFT JOIN Membresia m ON c.IdMembresia = m.IdMembresia
    WHERE p.IdPago = ?";
    
    $stmt = $conectador->prepare($sql);
    $stmt->bind_param("i", $id_pago);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $pago = $result->fetch_assoc();
        
        // Formatear tiempo estacionado
        $horas_est = floor($pago['MinutosEstacionado'] / 60);
        $minutos_est = $pago['MinutosEstacionado'] % 60;
        $tiempo_estacionado = $horas_est . "h " . $minutos_est . "m";
    } else {
        die("Pago no encontrado");
    }
} else {
    die("ID de pago no proporcionado");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago - Parqueo</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Parqueo</h1>
            <p>Comprobante de Pago</p>
        </header>

        <div class="receipt-container">
            <div class="receipt-header">
                <h2>Comprobante de Pago #<?php echo $pago['IdPago']; ?></h2>
                <p class="receipt-date">Fecha: <?php echo date('d/m/Y H:i', strtotime($pago['FechaPago'])); ?></p>
            </div>

            <div class="receipt-details">
                <div class="detail-section">
                    <h3>Información del Vehículo</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Placa:</span>
                            <span class="value"><?php echo $pago['Placa']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Tipo:</span>
                            <span class="value"><?php echo $pago['TipoVehiculo']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Espacio:</span>
                            <span class="value"><?php echo $pago['Zona'] . ' - ' . $pago['NumeroEspacio']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Información del Pago</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Método de Pago:</span>
                            <span class="value"><?php echo $pago['MetodoPago']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Hora de Entrada:</span>
                            <span class="value"><?php echo date('Y-m-d H:i', strtotime($pago['FechaHoraEntrada'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Hora de Salida:</span>
                            <span class="value"><?php echo date('Y-m-d H:i', strtotime($pago['FechaHoraSalida'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Tiempo Estacionado:</span>
                            <span class="value"><?php echo $tiempo_estacionado; ?></span>
                        </div>
                        <div class="detail-item highlight">
                            <span class="label">Total Pagado:</span>
                            <span class="value">Bs <?php echo number_format(    $pago['MontoTotal'], 2, '.', ','); ?></span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($pago['Nit']) || !empty($pago['RazonSocial'])): ?>
                <div class="detail-section">
                    <h3>Datos de Facturación</h3>
                    <div class="detail-grid">
                        <?php if (!empty($pago['Nit'])): ?>
                        <div class="detail-item">
                            <span class="label">NIT:</span>
                            <span class="value"><?php echo $pago['Nit']; ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pago['RazonSocial'])): ?>
                        <div class="detail-item">
                            <span class="label">Razón Social:</span>
                            <span class="value"><?php echo $pago['RazonSocial']; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="detail-section">
                    <h3>Información del Cliente</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">Cliente:</span>
                            <span class="value"><?php echo !empty($pago['Cliente']) ? $pago['Cliente'] : 'Cliente ocasional'; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Descuento aplicado:</span>
                            <span class="value"><?php echo $pago['Descuento']; ?>%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="receipt-footer">
                <p>¡Gracias por utilizar nuestro servicio de parqueo!</p>
                <p>Espacio liberado exitosamente</p>
            </div>

            <div class="actions">
                <button onclick="window.print()" class="btn-primary">Imprimir Comprobante</button>
                <a href="../../index.php" class="btn-secondary">Volver al Inicio</a>
            </div>
        </div>
    </div>
</body>
</html>