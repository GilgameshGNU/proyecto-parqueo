<?php
session_start();
include '../../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (!isset($_SESSION['ticket_data'])) {
    die("Error: No hay datos de ticket para imprimir");
}

$data = $_SESSION['ticket_data'];
$logoPath = '../vehiculo/img/logo.png';
$logoData = base64_encode(file_get_contents($logoPath));

$html = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .ticket {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2196f3;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 10px 0 5px;
            font-size: 24px;
            color: #333;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        .info {
            padding: 15px 0;
        }
        .info p {
            margin: 8px 0;
            line-height: 1.5;
            font-size: 16px;
        }
        .info strong {
            display: inline-block;
            width: 100px;
            color: #2196f3;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #2196f3;
            color: #666;
        }
        .footer p {
            font-style: italic;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class='ticket'>
        <div class='header'>
            <img src='data:image/png;base64,{$logoData}' class='logo'>
            <h2>Parqueo UEB</h2>
            <h3>Ticket de Estacionamiento</h3>
        </div>
        <div class='info'>
            <p><strong>Cliente:</strong> {$data['nombre']}</p>
            <p><strong>CI:</strong> {$data['ci']}</p>
            <p><strong>Vehículo:</strong> {$data['vehiculo']}</p>
            <p><strong>Placa:</strong> {$data['placa']}</p>
            <p><strong>Espacio:</strong> {$data['espacio']}</p>
            <p><strong>Entrada:</strong> " . date('d/m/Y H:i') . "</p>
            <p><strong>Monto:</strong> {$data['monto']} Bs</p>
        </div>
        <div class='footer'>
            <p>¡Gracias por su preferencia!</p>
            <p>Conserve este ticket para su salida</p>
        </div>
    </div>
</body>
</html>
";

$dompdf = new Dompdf([
    'isRemoteEnabled' => true
]);
$dompdf->loadHtml($html);
$dompdf->setPaper([0, 0, 226.77, 425.20]); // Tamaño más compacto
$dompdf->render();
$dompdf->stream("ticket_parqueo.pdf", ["Attachment" => true]);

unset($_SESSION['ticket_data']);
?>