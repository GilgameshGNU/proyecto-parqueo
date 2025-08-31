<?php
session_start();
require_once "../../db.php";

// Verificar que el ticket se creó correctamente
if (!isset($_SESSION['id_ticket'])) {
    die("Error: No se encontró información del ticket. Regrese al inicio.");
}

$idTicket = $_SESSION['id_ticket'];

// Obtener información completa del ticket
$query_ticket = mysqli_prepare($conectador, 
    "SELECT t.IdTicket, t.FechaHoraEntrada, t.Estado,
            c.NombreCompleto, c.Ci, c.IdMembresia, m.Nombre as MembresiaNombre, m.Descuento,
            v.Placa, v.Modelo, v.Marca, v.Color, tv.Nombre as TipoVehiculo,
            ep.NumeroEspacio, ep.Zona
     FROM Ticket t
     JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
     JOIN Cliente c ON cv.IdCliente = c.IdCliente
     JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
     JOIN TipoVehiculo tv ON v.IdTipo = tv.IdTipo
     JOIN EspacioParqueo ep ON t.IdEspacioParqueo = ep.IdEspacioParqueo
     LEFT JOIN Membresia m ON c.IdMembresia = m.IdMembresia
     WHERE t.IdTicket = ?");
     
if (!$query_ticket) {
    die("Error en la consulta: " . mysqli_error($conectador));
}

mysqli_stmt_bind_param($query_ticket, "i", $idTicket);
mysqli_stmt_execute($query_ticket);
$result_ticket = mysqli_stmt_get_result($query_ticket);
$ticket = mysqli_fetch_assoc($result_ticket);

if (!$ticket) {
    die("Error: Ticket no encontrado para ID: " . $idTicket);
}

// Obtener tarifa según tipo de vehículo
$query_tarifa = mysqli_prepare($conectador, 
    "SELECT Precio FROM Tarifa WHERE IdTipo = 
        (SELECT IdTipo FROM Vehiculo WHERE IdVehiculo = ?) 
     AND (FechaFin IS NULL OR FechaFin >= CURDATE()) 
     ORDER BY FechaInicio DESC LIMIT 1");
     
if (!$query_tarifa) {
    die("Error en la consulta de tarifa: " . mysqli_error($conectador));
}

mysqli_stmt_bind_param($query_tarifa, "i", $_SESSION['id_vehiculo']);
mysqli_stmt_execute($query_tarifa);
$result_tarifa = mysqli_stmt_get_result($query_tarifa);
$tarifa = mysqli_fetch_assoc($result_tarifa);
$precio_base = $tarifa['Precio'] ?? 0;

// Aplicar descuento de membresía si existe
$descuento = $ticket['Descuento'] ?? 0;
$monto_final = $precio_base - ($precio_base * ($descuento / 100));

// Guardar datos para el PDF
$_SESSION['ticket_data'] = [
    'nombre' => $ticket['NombreCompleto'],
    'ci' => $ticket['Ci'],
    'vehiculo' => $ticket['TipoVehiculo'],
    'placa' => $ticket['Placa'],
    'espacio' => $ticket['NumeroEspacio'],
    'monto' => number_format($monto_final, 2),
    'fecha_entrada' => $ticket['FechaHoraEntrada']
];

// Asignar variables para la vista
$cliente = [
    'NombreCompleto' => $ticket['NombreCompleto'],
    'Ci' => $ticket['Ci'],
    'Descuento' => $ticket['Descuento']
];

$vehiculo = [
    'Tipo' => $ticket['TipoVehiculo'],
    'Marca' => $ticket['Marca'],
    'Modelo' => $ticket['Modelo'],
    'Placa' => $ticket['Placa']
];

$numeroEspacio = $ticket['NumeroEspacio'];
?>