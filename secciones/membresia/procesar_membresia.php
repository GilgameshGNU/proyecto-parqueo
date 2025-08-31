<?php
include_once '../../db.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Obtener los valores del formulario
    $banco_union = isset($_POST['banco_union']) ? intval($_POST['banco_union']) : 0;
    $banco_ganadero = isset($_POST['banco_ganadero']) ? intval($_POST['banco_ganadero']) : 0;
    $banco_mercantil = isset($_POST['banco_mercantil']) ? intval($_POST['banco_mercantil']) : 0;
    
    // Validar que los porcentajes no excedan 100%
    $total = $banco_union + $banco_ganadero + $banco_mercantil;
    
    if ($total > 100) {
        $error = "El total de porcentajes no puede exceder el 100%";
    } elseif ($total < 0) {
        $error = "Los porcentajes no pueden ser negativos";
    } else {
        
        // Crear la tabla si no existe
        $create_table = "CREATE TABLE IF NOT EXISTS membresia_bancos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            porcentaje INT NOT NULL,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if (mysqli_query($conectador, $create_table)) {
            
            // Limpiar datos existentes
            $truncate = "TRUNCATE TABLE membresia_bancos";
            mysqli_query($conectador, $truncate);
            
            // Insertar nuevos datos
            $bancos = [
                ['Banco Unión', $banco_union],
                ['Banco Ganadero', $banco_ganadero],
                ['Banco Mercantil', $banco_mercantil]
            ];
            
            $success = true;
            foreach ($bancos as $banco) {
                $nombre = mysqli_real_escape_string($conectador, $banco[0]);
                $porcentaje = $banco[1];
                
                $insert = "INSERT INTO membresia_bancos (nombre, porcentaje) VALUES ('$nombre', $porcentaje)";
                if (!mysqli_query($conectador, $insert)) {
                    $success = false;
                    $error = "Error al insertar datos: " . mysqli_error($conectador);
                    break;
                }
            }
            
            if ($success) {
                $mensaje = "Membresía guardada exitosamente";
            }
            
        } else {
            $error = "Error al crear la tabla: " . mysqli_error($conectador);
        }
    }
}

// Redirigir con mensaje
if (isset($error)) {
    header("Location: index.php?error=" . urlencode($error));
} elseif (isset($mensaje)) {
    header("Location: ver.php?success=" . urlencode($mensaje));
} else {
    header("Location: index.php");
}
exit();
?>
