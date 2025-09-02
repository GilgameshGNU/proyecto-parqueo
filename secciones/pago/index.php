<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ticket = $_POST["id_ticket"];

    
    $sql = "SELECT 
        t.IdTicket,
        t.FechaHoraEntrada,
        v.Placa,
        tv.Nombre AS TipoVehiculo,
        c.IdCliente,
        COALESCE(m.Descuento, 0) AS Descuento,
        ta.Precio AS TarifaHora,
        TIMESTAMPDIFF(MINUTE, t.FechaHoraEntrada, NOW()) AS MinutosEstacionado,
        ep.IdEspacioParqueo
    FROM Ticket t
    INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
    INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
    INNER JOIN TipoVehiculo tv ON v.IdTipo = tv.IdTipo
    INNER JOIN EspacioParqueo ep ON t.IdEspacioParqueo = ep.IdEspacioParqueo
    LEFT JOIN Cliente c ON cv.IdCliente = c.IdCliente
    LEFT JOIN Membresia m ON c.IdMembresia = m.IdMembresia
    LEFT JOIN Tarifa ta ON ta.IdTipo = v.IdTipo 
    WHERE t.IdTicket = ? AND t.Estado = 'Abierto'";
    
    $stmt = $conectador->prepare($sql);
    $stmt->bind_param("i", $id_ticket);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
        
        // Calcular costo
        $horas = ceil($ticket['MinutosEstacionado'] / 60);
        $costo_sin_descuento = $horas * $ticket['TarifaHora'];
        $descuento = $costo_sin_descuento * ($ticket['Descuento'] / 100);
        $costo_total = $costo_sin_descuento - $descuento;
        
        // Formatear tiempo estacionado
        $horas_est = floor($ticket['MinutosEstacionado'] / 60);
        $minutos_est = $ticket['MinutosEstacionado'] % 60;
        $tiempo_estacionado = $horas_est . "h " . $minutos_est . "m";
        
        // Guardar en sesión para usar en el procesamiento
        $_SESSION['ticket_pago'] = [
            'id_ticket' => $ticket['IdTicket'],
            'costo_total' => $costo_total,
            'descuento' => $ticket['Descuento'],
            'id_espacio' => $ticket['IdEspacioParqueo']
        ];
    } else {
        die("Ticket no encontrado o ya cerrado");
    }
} else {
    die("ID de ticket no proporcionado");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pago - Parqueo</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Parqueo</h1>
            <p>Procesamiento de Pago</p>
        </header>

        <div class="payment-container">
            <div class="info-section">
                <h2>Información de Salida</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Ticket #:</span>
                        <span class="value"><?php echo $ticket['IdTicket']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Placa:</span>
                        <span class="value"><?php echo $ticket['Placa']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Fecha y Hora de entrada:</span>
                        <span class="value"><?php echo date('Y-m-d H:i', strtotime($ticket['FechaHoraEntrada'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Fecha y Hora de salida:</span>
                        <span class="value"><?php echo date('Y-m-d H:i'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Descuento:</span>
                        <span class="value"><?php echo $ticket['Descuento']; ?>%</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Tiempo Estacionado:</span>
                        <span class="value"><?php echo $tiempo_estacionado; ?></span>
                    </div>
                    <div class="info-item highlight">
                        <span class="label">Costo Total:</span>
                        <span class="value">Bs <?php echo number_format($costo_total, 2, ',', '.'); ?></span>
                    </div>
                </div>
            </div>

            <form id="payment-form" action="create.php" method="POST">
                <input type="hidden" name="id_ticket" value="<?php echo $ticket['IdTicket']; ?>">
                <input type="hidden" name="costo_total" value="<?php echo $costo_total; ?>">
                <input type="hidden" name="horasalida" value="<?php echo date('Y-m-d H:i:s'); ?>">
                
                <div class="form-section">
                    <h2>Método de Pago</h2>
                    <div class="payment-methods">
                        <div class="method-option">
                            <input type="radio" id="efectivo" name="metodo_pago" value="Efectivo" checked>
                            <label for="efectivo">Efectivo</label>
                        </div>
                        <div class="method-option">
                            <input type="radio" id="qr" name="metodo_pago" value="QR">
                            <label for="qr">Pago con QR</label>
                        </div>
                    </div>
                </div>

                <div class="form-section" id="qr-section" style="display: none;">
                    <h3>Pago con QR</h3>
                    <div class="qr-code">
                        <img src="./img/qrcode.png" alt="Código QR para pago">
                        <p>Escanea el código QR para realizar el pago</p>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Datos de Facturación</h3>
                    <div class="form-group">
                        <label for="nit">NIT:</label>
                        <input type="text" id="nit" name="nit" placeholder="Ingrese su NIT">
                    </div>
                    <div class="form-group">
                        <label for="razon_social">Razón Social:</label>
                        <input type="text" id="razon_social" name="razon_social" placeholder="Ingrese razón social">
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary">Procesar Pago</button>
                    <a href="../index.php" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script src="./js/script.js"></script>
</body>
</html>