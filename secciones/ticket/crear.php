<?php
require_once '../../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Aquí puedes obtener los datos del ticket, por ejemplo desde GET o POST
$nombre = $_GET['nombre'] ?? '';
$ci = $_GET['ci'] ?? '';
$vehiculo = $_GET['vehiculo'] ?? '';
$placa = $_GET['placa'] ?? '';
$espacio = $_GET['espacio'] ?? '';
$fecha = $_GET['fecha'] ?? date('d/m/Y H:i');
$monto = $_GET['monto'] ?? '';

$html = "
  <h1 style='text-align:center;'>Ticket</h1>
  <hr>
  <p><strong>Nombre del cliente:</strong> $nombre</p>
  <p><strong>CI:</strong> $ci</p>
  <p><strong>Vehículo:</strong> $vehiculo</p>
  <p><strong>Placa:</strong> $placa</p>
  <p><strong>Espacio asignado:</strong> $espacio</p>
  <p><strong>Fecha de entrada:</strong> $fecha</p>
  <p><strong>Monto a pagar:</strong> $monto Bs</p>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();
$dompdf->stream("ticket.pdf", ["Attachment" => true]);
?>