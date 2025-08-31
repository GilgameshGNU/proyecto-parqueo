<?php
session_start();
require_once "../../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $ci = trim($_POST['ci'] ?? '');

    // Validaciones
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }

    if (strlen($nombre) > 100) {
        $errores[] = "El nombre no puede exceder los 100 caracteres";
    }

    if (!empty($telefono) && strlen($telefono) > 15) {
        $errores[] = "El teléfono no puede exceder los 15 caracteres";
    }

    if (!empty($ci) && strlen($ci) > 15) {
        $errores[] = "El CI no puede exceder los 15 caracteres";
    }

    // Si hay errores, volver al formulario
    if (!empty($errores)) {
        $_SESSION['error'] = implode("<br>", $errores);
        $_SESSION['cliente_temp'] = [
            'nombre' => $nombre,
            'telefono' => $telefono,
            'ci' => $ci
        ];
        header("Location: index.php");
        exit;
    }

    // Guardar datos temporalmente en sesión (NO en BD todavía)
    $_SESSION['cliente_temp'] = [
        'nombre' => $nombre,
        'telefono' => $telefono,
        'ci' => $ci
    ];
    
    // Redirigir al siguiente formulario
    header("Location: ../vehiculo/index.php");
    exit;
} else {
    // Si no es POST, redirigir al formulario
    header("Location: index.php");
    exit;
}
?>