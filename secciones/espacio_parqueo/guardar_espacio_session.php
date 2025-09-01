<?php
session_start();
include "../../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar datos
    if (!isset($data['id_espacio']) || !isset($data['numero_espacio'])) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }
    
    // Guardar espacio en sesión
    $_SESSION['id_espacio'] = $data['id_espacio'];
    $_SESSION['numero_espacio'] = $data['numero_espacio'];
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>