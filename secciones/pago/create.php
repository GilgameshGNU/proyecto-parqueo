<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto-parqueo/login.php");
    exit();
}
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $id_ticket = $_POST["id_ticket"];
    $costo_total = $_POST["costo_total"];
    $hora_salida = $_POST["horasalida"];
    $metodo_pago = $_POST["metodo_pago"];
    
    // Obtener datos de facturación si es pago con QR
    $nit = isset($_POST["nit"]) ? $_POST["nit"] : NULL;
    $razon_social = isset($_POST["razon_social"]) ? $_POST["razon_social"] : NULL;
    
    // Obtener información del ticket desde la sesión
    $id_espacio = $_SESSION['ticket_pago']['id_espacio'];
    
    // Iniciar transacción
    $conectador->begin_transaction();
    
    try {
        // 1. Actualizar ticket - cerrarlo y establecer hora de salida y costo
        $sql_ticket = "UPDATE Ticket 
                      SET FechaHoraSalida = ?, CostoTotal = ?, Estado = 'Cerrado' 
                      WHERE IdTicket = ?";
        $stmt_ticket = $conectador->prepare($sql_ticket);
        $stmt_ticket->bind_param("sdi", $hora_salida, $costo_total, $id_ticket);
        $stmt_ticket->execute();
        
        // 2. Liberar espacio de parqueo
        $sql_espacio = "UPDATE EspacioParqueo SET Estado = 'Disponible' WHERE IdEspacioParqueo = ?";
        $stmt_espacio = $conectador->prepare($sql_espacio);
        $stmt_espacio->bind_param("i", $id_espacio);
        $stmt_espacio->execute();
        
        // 3. Registrar pago
        $sql_pago = "INSERT INTO Pago (Nit, RazonSocial, MontoTotal, MetodoPago, Estado, IdTicket) 
                    VALUES (?, ?, ?, ?, 'Pagado', ?)";
        $stmt_pago = $conectador->prepare($sql_pago);
        $stmt_pago->bind_param("ssdsi", $nit, $razon_social, $costo_total, $metodo_pago, $id_ticket);
        $stmt_pago->execute();
        $id_pago = $conectador->insert_id;
        
        // Confirmar transacción
        $conectador->commit();
        
        // Redirigir a la página de detalle con el ID del pago
        header("Location: detalle.php?id_pago=" . $id_pago);
        exit();
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conectador->rollback();
        echo "Error al procesar el pago: " . $e->getMessage();
    }
} else {
    echo "<h2>Error: Método de acceso incorrecto</h2>";
    echo "<p>Esta página debe ser accedida mediante el formulario de pago.</p>";
}
?>