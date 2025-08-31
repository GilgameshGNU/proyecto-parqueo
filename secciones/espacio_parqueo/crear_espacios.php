<?php
require_once "../../db.php";

// Insertar espacios para planta alta
for ($fila = 1; $fila <= 6; $fila++) {
    for ($col = 1; $col <= 8; $col++) {
        $numero = "A-" . str_pad((($fila - 1) * 8 + $col), 2, "0", STR_PAD_LEFT);
        $zona = "Planta alta";
        $estado = "Disponible";
        $idTipo = null; // Puedes asignar el tipo si lo necesitas
        $sql = "INSERT INTO EspacioParqueo (NumeroEspacio, Estado, Zona, IdTipo) VALUES ('$numero', '$estado', '$zona', NULL)";
        mysqli_query($conectador, $sql);
    }
}

// Insertar espacios para planta baja
for ($fila = 1; $fila <= 6; $fila++) {
    for ($col = 1; $col <= 10; $col++) {
        $numero = "B-" . str_pad((($fila - 1) * 10 + $col), 2, "0", STR_PAD_LEFT);
        $zona = "Planta baja";
        $estado = "Disponible";
        $idTipo = null;
        $sql = "INSERT INTO EspacioParqueo (NumeroEspacio, Estado, Zona, IdTipo) VALUES ('$numero', '$estado', '$zona', NULL)";
        mysqli_query($conectador, $sql);
    }
}

echo "Espacios insertados correctamente.";
?>