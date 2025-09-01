<?php
session_start();
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ticket = $_POST["id_ticket"];
    $hora_salida = $_POST["horasalida"];
    
    // Iniciar transacción
    $conectador->begin_transaction();
    
    try {
        // Obtiene la información del ticket y espacio
        $sql_info = "SELECT t.IdTicket, t.IdEspacioParqueo, ep.NumeroEspacio, ep.Zona
                     FROM Ticket t
                     JOIN EspacioParqueo ep ON t.IdEspacioParqueo = ep.IdEspacioParqueo
                     WHERE t.IdTicket = ? AND t.Estado = 'Abierto'";
        
        $stmt_info = $conectador->prepare($sql_info);
        $stmt_info->bind_param("i", $id_ticket);
        $stmt_info->execute();
        $result_info = $stmt_info->get_result();
        
        if ($result_info->num_rows === 0) {
            throw new Exception("Ticket no encontrado o ya cerrado");
        }
        
        $ticket_info = $result_info->fetch_assoc();
        $id_espacio = $ticket_info['IdEspacioParqueo'];
        
        // Actualiza el ticket - cerrarlo y establecer hora de salida
        $sql_ticket = "UPDATE Ticket 
                      SET FechaHoraSalida = ?, Estado = 'Cerrado' 
                      WHERE IdTicket = ?";
        $stmt_ticket = $conectador->prepare($sql_ticket);
        $stmt_ticket->bind_param("si", $hora_salida, $id_ticket);
        $stmt_ticket->execute();
        
        // Liberar espacio del parqueo - CAMBIAR A DISPONIBLE
        $sql_espacio = "UPDATE EspacioParqueo SET Estado = 'Disponible' WHERE IdEspacioParqueo = ?";
        $stmt_espacio = $conectador->prepare($sql_espacio);
        $stmt_espacio->bind_param("i", $id_espacio);
        $stmt_espacio->execute();
        
        // Confirmar transacción
        $conectador->commit();
        
        // Respuesta exitosa
        $response = [
            'success' => true,
            'message' => 'Vehículo salió exitosamente',
            'espacio' => $ticket_info['NumeroEspacio'],
            'zona' => $ticket_info['Zona']
        ];
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conectador->rollback();
        
        $response = [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
        
        echo json_encode($response);
    }
} else {
    $response = [
        'success' => false,
        'message' => 'Método de acceso incorrecto'
    ];
    
    echo json_encode($response);
}
?>
