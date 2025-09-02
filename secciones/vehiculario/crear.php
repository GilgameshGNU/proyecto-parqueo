<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
include(__DIR__ . "/../../db.php");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idClienteVeh = $_POST['clienteVeh'];
    $idEspacio = $_POST['espacio'];

    $sql = "INSERT INTO Ticket (FechaHoraEntrada, Estado, IdClienteVeh, IdEspacioParqueo) 
            VALUES (NOW(), 'Abierto', '$idClienteVeh', '$idEspacio')";
    if (mysqli_query($conectador, $sql)) {
        echo "<script>alert('Vehículo registrado en el parqueo'); window.location='index.php';</script>";
    } else {
        echo "❌ Error: " . mysqli_error($conectador);
    }
}

// Obtener clientes + vehículos
$clientesVehiculos = mysqli_query($conectador, "
    SELECT cv.IdClienteVeh, c.NombreCompleto, v.Placa 
    FROM ClienteVehiculos cv
    INNER JOIN Cliente c ON cv.IdCliente = c.IdCliente
    INNER JOIN Vehiculo v ON cv.IdVehiculo = v.IdVehiculo");

// Espacios disponibles
$espacios = mysqli_query($conectador, "SELECT IdEspacioParqueo, NumeroEspacio, Zona 
                                       FROM EspacioParqueo WHERE Estado = 'Disponible'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar vehículo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Vehículo</h2>
        <form method="POST">
            <label>Cliente - Vehículo:</label><br>
            <select name="clienteVeh" required>
                <?php while($cv = mysqli_fetch_assoc($clientesVehiculos)) { ?>
                    <option value="<?php echo $cv['IdClienteVeh']; ?>">
                        <?php echo $cv['NombreCompleto']." - ".$cv['Placa']; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <label>Espacio de Parqueo:</label><br>
            <select name="espacio" required>
                <?php while($e = mysqli_fetch_assoc($espacios)) { ?>
                    <option value="<?php echo $e['IdEspacioParqueo']; ?>">
                        <?php echo $e['NumeroEspacio']." - ".$e['Zona']; ?>
                    </option>
                <?php } ?>
            </select><br><br>

            <button type="submit" class="btn-edit">Registrar</button>
            <a href="menu.php" class="btn-ver">Volver</a>
        </form>
    </div>
</body>
</html>
