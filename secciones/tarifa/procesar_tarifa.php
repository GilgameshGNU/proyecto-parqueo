<?php
include("../../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];

    $query = "UPDATE Tarifa SET Precio = '$precio', IdTipo = '$tipo' WHERE IdTarifa = '$id'";

    if (mysqli_query($conectador, $query)) {
        header("Location: tarifas.php?msg=ok");
    } else {
        echo "Error al actualizar: " . mysqli_error($conectador);
    }
}
?>
