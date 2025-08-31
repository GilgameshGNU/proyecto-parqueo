<?php
//dominio, usuario, contraseña, nombre_base_de_datos
$conectador = mysqli_connect("localhost", "root", "", "Parqueo");
// Verificar conexión
if (!$conectador) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}
//zona-horaria es para que el registro sea exacto
date_default_timezone_set('America/La_Paz');
mysqli_query($conectador, "SET NAMES 'utf8'");
?>