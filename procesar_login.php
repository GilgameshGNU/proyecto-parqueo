<?php
// filepath: c:\laragon\www\proyecto-parqueo\procesar_login.php
session_start();
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $contra = $_POST['contra'] ?? '';

    $sql = "SELECT * FROM Usuario WHERE Nombre = ? AND Contra = ?";
    $stmt = mysqli_prepare($conectador, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $contra);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        $_SESSION['usuario'] = $usuario['Nombre'];
        $_SESSION['rol'] = $usuario['Rol'];
        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?error=1");
        exit;
    }
}
?>