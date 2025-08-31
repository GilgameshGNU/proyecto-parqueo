<?php
session_start();
require_once "../../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = trim($_POST['placa'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $idTipo = $_POST['tipo_vehiculo'] ?? null;
    $idMembresia = $_POST['membresia'] ?? null;

    // Validaciones
    $errores = [];
    
    if (empty($placa)) {
        $errores[] = "La placa es obligatoria";
    }

    if (strlen($placa) > 15) {
        $errores[] = "La placa no puede exceder los 15 caracteres";
    }

    if (!empty($modelo) && strlen($modelo) > 30) {
        $errores[] = "El modelo no puede exceder los 30 caracteres";
    }

    if (!empty($marca) && strlen($marca) > 30) {
        $errores[] = "La marca no puede exceder los 30 caracteres";
    }

    if (!empty($color) && strlen($color) > 30) {
        $errores[] = "El color no puede exceder los 30 caracteres";
    }

    if (empty($idTipo)) {
        $errores[] = "Debe seleccionar un tipo de vehículo";
    }

    // Si hay errores, volver al formulario
    if (!empty($errores)) {
        $_SESSION['error_vehiculo'] = implode("<br>", $errores);
        $_SESSION['vehiculo_temp'] = [
            'placa' => $placa,
            'modelo' => $modelo,
            'marca' => $marca,
            'color' => $color,
            'tipo_vehiculo' => $idTipo,
            'membresia' => $idMembresia
        ];
        header("Location: index.php");
        exit;
    }

    // Guardar datos temporalmente en sesión (NO en BD todavía)
    $_SESSION['vehiculo_temp'] = [
        'placa' => $placa,
        'modelo' => $modelo,
        'marca' => $marca,
        'color' => $color,
        'tipo_vehiculo' => $idTipo,
        'membresia' => $idMembresia
    ];
    
    // Redirigir al mapa de espacios
    header("Location: ../espacio_parqueo/index.php");
    exit;
} else {
    // Si no es POST, redirigir al formulario
    header("Location: index.php");
    exit;
}
?>