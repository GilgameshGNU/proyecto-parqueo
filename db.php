<?php
//dominio,name_user,contraseña,nom_basededatos
$conectador=mysqli_connect("localhost","debian-sys-maint","kSfwej9pDMhY1Hnv","Parqueo");
// Verificar conexión
if (!$conectador) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}
//zona-horaria es para que el registro sea exacto
date_default_timezone_set('America/La_Paz');
mysqli_query($conectador,"SET charset 'utf8'");
?>