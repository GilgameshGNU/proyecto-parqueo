<?php
include("../../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    if ($accion == "crear") {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descuento = $_POST['descuento'] ?? 0;
        $estado = $_POST['estado'];

        $sql = "INSERT INTO Membresia (Nombre, Precio, Descuento, Estado) 
                VALUES ('$nombre','$precio','$descuento','$estado')";
    } elseif ($accion == "editar") {
        $id = $_POST['id'];
        $precio = $_POST['precio'];
        $descuento = $_POST['descuento'];
        $estado = $_POST['estado'];

        $sql = "UPDATE Membresia SET Precio='$precio', Descuento='$descuento', Estado='$estado' 
                WHERE IdMembresia=$id";
    }

    if (mysqli_query($conectador, $sql)) {
        header("Location: membresias.php?msg=ok");
    } else {
        echo "Error: " . mysqli_error($conectador);
    }
}
