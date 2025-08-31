[file name]: crear.php
[file content begin]
<?php
session_start();
require_once '../../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (!isset($_SESSION['ticket_data'])) {
    die("Error: No hay datos de ticket para imprimir");
}

$data = $_SESSION['ticket_data'];

$html = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 5px;
        }
        .ticket {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 10px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555;
        }
        .info p {
            margin: 5px 0;
            line-height: 1.3;
        }
        .info strong {
            display: inline-block;
            width: 70px;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-style: italic;
            color: #777;
        }
    </style>
</head>
<body>
    <div class='ticket'>
        <div class='header'>
            <h2>PARQUEO UEB</h2>
            <h3>TICKET DE ESTACIONAMIENTO</h3>
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
        </div>
    </div>
</body>
</html>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A7', 'portrait'); // Tamaño ticket
$dompdf->render();
$dompdf->stream("ticket_parqueo.pdf", ["Attachment" => true]);

// Limpiar datos después de imprimir
unset($_SESSION['ticket_data']);
?>
[file content end]