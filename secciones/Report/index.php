<?php
// pages/reportes.php
session_start();
include '../../db.php';
include '../../templates/header.php';
// --- Filtros (valores por defecto: mes actual)
$hoy = new DateTime('now');
$desde_default = $hoy->format('Y-m'); // YYYY-MM
$hasta_default = $hoy->format('Y-m');

$desde = isset($_GET['desde']) ? $_GET['desde'] : $desde_default; // YYYY-MM
$hasta = isset($_GET['hasta']) ? $_GET['hasta'] : $hasta_default; // YYYY-MM
$metodo = isset($_GET['metodo']) ? $_GET['metodo'] : 'Todos';

// Normalizar a rangos de fecha (primer día/último día del mes)
function monthToRange($ym)
{
    // $ym: 'YYYY-MM'
    $first = DateTime::createFromFormat('Y-m-d', $ym . '-01');
    $last = clone $first;
    $last->modify('last day of this month');
    return [$first->format('Y-m-d'), $last->format('Y-m-d')];
}

list($desdeDate, $hastaDate) = monthToRange($desde);
list($desdeDate2, $hastaDate2) = monthToRange($hasta);

// --- QUERIES ---
// 1) Ganancia por estacionamiento (Pago.MontoTotal) estado Pagado
// Filtro por metodo si corresponde
$params = [$desdeDate, $hastaDate2];
$whereMetodo = '';
if ($metodo !== 'Todos') {
    $whereMetodo = " AND p.MetodoPago = ? ";
    $params[] = $metodo;
}

$sqlParkingTotal = "
    SELECT COALESCE(SUM(p.MontoTotal),0) AS total
    FROM Pago p
    WHERE p.Estado = 'Pagado'
      AND p.FechaPago BETWEEN ? AND ?
      $whereMetodo
";
$stmt = $conectador->prepare($sqlParkingTotal);
if ($metodo !== 'Todos') {
    $stmt->bind_param("sss", $params[0], $params[1], $params[2]);
} else {
    $stmt->bind_param("ss", $params[0], $params[1]);
}
$stmt->execute();
$parkingTotal = $stmt->get_result()->fetch_assoc()['total'] ?? 0.00;
$stmt->close();

// 2) Ganancia por membresías (asunción temporal):
//    Sumamos Membresia.Precio por FechaInicio en el rango y Estado='Activa'
$sqlMembresiaTotal = "
    SELECT COALESCE(SUM(Precio),0) AS total
    FROM Membresia
      WHERE DATE(FechaInicio) BETWEEN ? AND ?
";
$stmt = $conectador->prepare($sqlMembresiaTotal);
$stmt->bind_param("ss", $desdeDate, $hastaDate2);
$stmt->execute();
$membresiaTotal = $stmt->get_result()->fetch_assoc()['total'] ?? 0.00;
$stmt->close();

$totalGeneral = (float) $parkingTotal + (float) $membresiaTotal;

// 3) Detalle mensual: estacionamiento por mes
$sqlParkingMensual = "
    SELECT DATE_FORMAT(p.FechaPago, '%Y-%m') AS ym, COALESCE(SUM(p.MontoTotal),0) AS total
    FROM Pago p
    WHERE p.Estado='Pagado'
      AND p.FechaPago BETWEEN ? AND ?
      $whereMetodo
    GROUP BY ym
    ORDER BY ym
";
$stmt = $conectador->prepare($sqlParkingMensual);
if ($metodo !== 'Todos') {
    $stmt->bind_param("sss", $params[0], $params[1], $params[2]);
} else {
    $stmt->bind_param("ss", $params[0], $params[1]);
}
$stmt->execute();
$resParkingMensual = $stmt->get_result();
$parkingPorMes = [];
while ($r = $resParkingMensual->fetch_assoc()) {
    $parkingPorMes[$r['ym']] = (float) $r['total'];
}
$stmt->close();

// 4) Detalle mensual: membresías por mes (por FechaInicio, Estado Activa)
$sqlMembresiaMensual = "
    SELECT DATE_FORMAT(FechaInicio, '%Y-%m') AS ym, COALESCE(SUM(Precio),0) AS total
    FROM Membresia
      WHERE DATE(FechaInicio) BETWEEN ? AND ?
    GROUP BY ym
    ORDER BY ym
";
$stmt = $conectador->prepare($sqlMembresiaMensual);
$stmt->bind_param("ss", $desdeDate, $hastaDate2);
$stmt->execute();
$resMembresiaMensual = $stmt->get_result();
$membresiaPorMes = [];
while ($r = $resMembresiaMensual->fetch_assoc()) {
    $membresiaPorMes[$r['ym']] = (float) $r['total'];
}
$stmt->close();

// Construir lista de meses del rango para tabla unificada
$meses = [];
$cursor = DateTime::createFromFormat('Y-m-d', $desdeDate);
$fin = DateTime::createFromFormat('Y-m-d', $hastaDate2);
while ($cursor <= $fin) {
    $meses[] = $cursor->format('Y-m');
    $cursor->modify('first day of next month');
}

// 5) Detalle de pagos (lista)
$sqlDetallePagos = "
    SELECT p.IdPago, p.FechaPago, p.MetodoPago, p.MontoTotal, p.Estado, p.IdTicket
    FROM Pago p
    WHERE p.Estado='Pagado'
      AND p.FechaPago BETWEEN ? AND ?
      $whereMetodo
    ORDER BY p.FechaPago DESC, p.IdPago DESC
";
$stmt = $conectador->prepare($sqlDetallePagos);
if ($metodo !== 'Todos') {
    $stmt->bind_param("sss", $params[0], $params[1], $params[2]);
} else {
    $stmt->bind_param("ss", $params[0], $params[1]);
}
$stmt->execute();
$detallePagos = $stmt->get_result();
include '../../templates/footer.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reportes - Parqueo</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <button id="Back">
            Back
    </button>
    <div class="container">
        <header>
            <h1>Sistema de Parqueo</h1>
            <p>Reportes de Ingresos</p>
        </header>

        <div class="report-container">
            <div class="form-section">
                <h2>Filtros</h2>
                <form method="GET" class="filters">
                    <div class="form-group">
                        <label for="desde">Desde (mes):</label>
                        <input type="month" id="desde" name="desde" value="<?php echo htmlspecialchars($desde); ?>">
                    </div>
                    <div class="form-group">
                        <label for="hasta">Hasta (mes):</label>
                        <input type="month" id="hasta" name="hasta" value="<?php echo htmlspecialchars($hasta); ?>">
                    </div>
                    <div class="form-group">
                        <label for="metodo">Método de pago:</label>
                        <select id="metodo" name="metodo">
                            <option <?php echo $metodo === 'Todos' ? 'selected' : ''; ?> value="Todos">Todos</option>
                            <option <?php echo $metodo === 'Efectivo' ? 'selected' : ''; ?> value="Efectivo">Efectivo
                            </option>
                            <option <?php echo $metodo === 'QR' ? 'selected' : ''; ?> value="QR">QR</option>
                        </select>
                    </div>
                    <div class="actions">
                        <button type="submit" class="btn-primary">Aplicar filtros</button>
                    </div>
                </form>
            </div>

            <div class="summary-cards">
                <div class="card">
                    <div class="label">Ganancia Estacionamiento</div>
                    <div class="value">$<?php echo number_format($parkingTotal, 2); ?></div>
                </div>
                <div class="card">
                    <div class="label">Ganancia Membresías*</div>
                    <div class="value">$<?php echo number_format($membresiaTotal, 2); ?></div>
                </div>
                <div class="card">
                    <div class="label">Total</div>
                    <div class="value">$<?php echo number_format($totalGeneral, 2); ?></div>
                </div>
            </div>

            <div class="info-section" style="border-bottom:none; margin-top:20px;">
            </div>
        </div>

        <div class="report-container">
            <h2>Detalle Mensual</h2>
            <table>
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Estacionamiento</th>
                        <th>Membresías*</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($meses as $ym):
                        $p = $parkingPorMes[$ym] ?? 0.00;
                        $m = $membresiaPorMes[$ym] ?? 0.00;
                        $tt = $p + $m;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ym); ?></td>
                            <td>$<?php echo number_format($p, 2); ?></td>
                            <td>$<?php echo number_format($m, 2); ?></td>
                            <td><strong>$<?php echo number_format($tt, 2); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($meses)): ?>
                        <tr>
                            <td colspan="4">Sin datos en el rango seleccionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="report-container">
            <h2>Detalle de Pagos (Estacionamiento)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Fecha Pago</th>
                        <th>Id Pago</th>
                        <th>Id Ticket</th>
                        <th>Método</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($detallePagos->num_rows > 0): ?>
                        <?php while ($p = $detallePagos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['FechaPago']); ?></td>
                                <td>#<?php echo (int) $p['IdPago']; ?></td>
                                <td>#<?php echo (int) $p['IdTicket']; ?></td>
                                <td><?php echo htmlspecialchars($p['MetodoPago']); ?></td>
                                <td>$<?php echo number_format($p['MontoTotal'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Sin pagos en el rango seleccionado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        

        <div class="receipt-footer">
            <p>Reporte generado para el rango: <?php echo htmlspecialchars($desde); ?> a
                <?php echo htmlspecialchars($hasta); ?><?php echo $metodo !== 'Todos' ? " — Método: " . htmlspecialchars($metodo) : ''; ?>
            </p>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>
