<?php
include "../../db.php";

$espacios = [];
$query = mysqli_query($conectador, "SELECT * FROM EspacioParqueo ORDER BY Zona, NumeroEspacio ASC");
while ($row = mysqli_fetch_assoc($query)) {
    $espacios[] = $row;
}
header('Content-Type: application/json');
echo json_encode($espacios);
?>