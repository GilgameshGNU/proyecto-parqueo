<?php
include(__DIR__ . "/../../db.php");

$id = $_GET['id'];

// Verificar si existe el ticket
$sql = "SELECT t.IdTicket, v.Placa, v.Modelo, v.Marca, v.Color, e.NumeroEspacio, e.Zona 
        FROM Ticket t
        INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
        INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
        INNER JOIN EspacioParqueo e ON t.IdEspacioParqueo = e.IdEspacioParqueo
        WHERE t.IdTicket = $id";

$result = mysqli_query($conectador, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert(' Ticket no encontrado'); window.location='index.php';</script>";
    exit;
}

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteSql = "DELETE FROM Ticket WHERE IdTicket = $id";
    if (mysqli_query($conectador, $deleteSql)) {
        echo "<script>alert(' Ticket eliminado correctamente'); window.location='index.php';</script>";
    } else {
        echo " Error: " . mysqli_error($conectador);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Ticket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Eliminar Ticket</h2>
        <p>¿Seguro que deseas eliminar este ticket? Esta acción no se puede deshacer.</p>

        <p><b>Placa:</b> <?php echo $data['Placa']; ?></p>
        <p><b>Modelo:</b> <?php echo $data['Modelo']; ?></p>
        <p><b>Marca:</b> <?php echo $data['Marca']; ?></p>
        <p><b>Color:</b> <?php echo $data['Color']; ?></p>
        <p><b>Espacio:</b> <?php echo $data['NumeroEspacio']." (".$data['Zona'].")"; ?></p>

        <form method="POST">
            <button type="submit" class="btn-del">Sí, eliminar</button>
            <a href="menu.php" class="btn-ver">Cancelar</a>
        </form>
    </div>
</body>
</html>
