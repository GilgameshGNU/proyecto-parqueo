<?php
include "../../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $idEspacio = $data['id'];
    $nuevoEstado = $data['estado'];
    
    // Validar que el estado sea uno de los permitidos
    $estadosPermitidos = ['Disponible', 'Mantenimiento']; // Solo estos dos valores
    if (!in_array($nuevoEstado, $estadosPermitidos)) {
        echo json_encode(['success' => false, 'error' => 'Estado no válido. Use: Disponible o Mantenimiento']);
        exit;
    }
    
    $sql = "UPDATE EspacioParqueo SET Estado = ? WHERE IdEspacioParqueo = ?";
    $stmt = mysqli_prepare($conectador, $sql);
    mysqli_stmt_bind_param($stmt, "si", $nuevoEstado, $idEspacio);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conectador)]);
    }
}
?>