<?php
include(__DIR__ . "/../../db.php");

$id = $_GET['id'];

// Obtener ticket
$sql = "SELECT t.IdTicket, v.IdVehiculo, v.Placa, v.Modelo, v.Marca, v.Color, e.IdEspacioParqueo, e.NumeroEspacio, e.Zona 
        FROM Ticket t
        INNER JOIN ClienteVehiculos cv ON t.IdClienteVeh = cv.IdClienteVeh
        INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo
        INNER JOIN EspacioParqueo e ON t.IdEspacioParqueo = e.IdEspacioParqueo
        WHERE t.IdTicket = $id";
$result = mysqli_query($conectador, $sql);
$data = mysqli_fetch_assoc($result);

// Procesar guardado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];
    $marca  = $_POST['marca'];
    $color  = $_POST['color'];
    $numeroEspacio = $_POST['numero_espacio'];

    // Actualizar Vehiculo
    $sqlVehiculo = "UPDATE Vehiculo 
                    SET Placa='$placa', Modelo='$modelo', Marca='$marca', Color='$color' 
                    WHERE IdVehiculo = {$data['IdVehiculo']}";
    mysqli_query($conectador, $sqlVehiculo);

    // Actualizar EspacioParqueo
    $sqlEspacio = "UPDATE EspacioParqueo 
                   SET NumeroEspacio='$numeroEspacio' 
                   WHERE IdEspacioParqueo = {$data['IdEspacioParqueo']}";
    mysqli_query($conectador, $sqlEspacio);

    echo "<script>alert('Datos guardados correctamente'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salida de Vehículo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Editar Vehículo</h2>

        <form method="POST">
            <label>Placa:</label>
            <input type="text" name="placa" value="<?php echo $data['Placa']; ?>" required>

            <p><label>Modelo:</label>
            <input type="text" name="modelo" value="<?php echo $data['Modelo']; ?>" required></p>

            <p><label>Marca:</label>
            <input type="text" name="marca" value="<?php echo $data['Marca']; ?>" required></p>

            <p><label>Color:</label>
            <input type="text" name="color" value="<?php echo $data['Color']; ?>" required></p>

            <p><label>Espacio:</label>
            <input type="text" name="numero_espacio" value="<?php echo $data['NumeroEspacio']; ?>" required></p>

            <button type="submit" class="btn-del">Guardar</button>
            <a href="menu.php" class="btn-ver">Cancelar</a>
        </form>
    </div>
</body>
</html>
