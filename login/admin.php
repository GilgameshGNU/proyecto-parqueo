<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'Administrador') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
</head>
<body>
    <h1>Bienvenido Administrador, <?php echo $_SESSION['usuario']; ?> </h1>
    <a href="cerrarsesion.php">Cerrar sesión</a>
</body>
</html>
