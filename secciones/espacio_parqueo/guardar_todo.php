<?php
session_start();
include "../../db.php";

// Verificar que todos los datos necesarios existan
if (!isset($_SESSION['cliente_temp']) || !isset($_SESSION['vehiculo_temp']) || !isset($_SESSION['id_espacio'])) {
    die("Error: Datos incompletos. Complete todos los formularios primero.");
}

// Iniciar transacción
mysqli_begin_transaction($conectador);

try {
    // Insertar cliente
    $cliente = $_SESSION['cliente_temp'];
    $sql_cliente = "INSERT INTO Cliente (NombreCompleto, Telefono, Ci, IdMembresia) 
                   VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conectador, $sql_cliente);
    $idMembresia = !empty($_SESSION['vehiculo_temp']['membresia']) ? $_SESSION['vehiculo_temp']['membresia'] : null;
    mysqli_stmt_bind_param($stmt, "sssi", $cliente['nombre'], $cliente['telefono'], 
                          $cliente['ci'], $idMembresia);
    mysqli_stmt_execute($stmt);
    $idCliente = mysqli_insert_id($conectador);

    // Insertar vehículo
    $vehiculo = $_SESSION['vehiculo_temp'];
    $sql_vehiculo = "INSERT INTO Vehiculo (Placa, Modelo, Marca, Color, IdTipo) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conectador, $sql_vehiculo);
    mysqli_stmt_bind_param($stmt, "ssssi", $vehiculo['placa'], $vehiculo['modelo'], 
                          $vehiculo['marca'], $vehiculo['color'], $vehiculo['tipo_vehiculo']);
    mysqli_stmt_execute($stmt);
    $idVehiculo = mysqli_insert_id($conectador);

    // Relaciona cliente-vehículo
    $sql_relacion = "INSERT INTO ClienteVehiculos (IdCliente, IdVehiculo) VALUES (?, ?)";
    $stmt = mysqli_prepare($conectador, $sql_relacion);
    mysqli_stmt_bind_param($stmt, "ii", $idCliente, $idVehiculo);
    mysqli_stmt_execute($stmt);
    $idClienteVehiculo = mysqli_insert_id($conectador);

    // Actualiza el espacio como ocupado
    $idEspacio = $_SESSION['id_espacio'];
    $sql_espacio = "UPDATE EspacioParqueo SET Estado = 'Mantenimiento' WHERE IdEspacioParqueo = ?";
    $stmt = mysqli_prepare($conectador, $sql_espacio);
    mysqli_stmt_bind_param($stmt, "i", $idEspacio);
    mysqli_stmt_execute($stmt);

    // Se crear ticket
    $sql_ticket = "INSERT INTO Ticket (IdClienteVeh, IdEspacioParqueo, FechaHoraEntrada, Estado) 
                  VALUES (?, ?, NOW(), 'Abierto')";
    $stmt = mysqli_prepare($conectador, $sql_ticket);
    mysqli_stmt_bind_param($stmt, "ii", $idClienteVehiculo, $idEspacio);
    mysqli_stmt_execute($stmt);
    $idTicket = mysqli_insert_id($conectador);

    // Se confirmar transacción
    mysqli_commit($conectador);

    // Guardar IDs en sesión para ticket
    $_SESSION['id_cliente'] = $idCliente;
    $_SESSION['id_vehiculo'] = $idVehiculo;
    $_SESSION['id_ticket'] = $idTicket;

    // Limpiar datos temporales
    unset($_SESSION['cliente_temp'], $_SESSION['vehiculo_temp']);

    // Redirigir a ticket
    header("Location: ../ticket/index.php");
    exit;

} catch (Exception $e) {
    // Revertir en caso de error
    mysqli_rollback($conectador);
    die("Error al guardar los datos: " . $e->getMessage());
}
?>