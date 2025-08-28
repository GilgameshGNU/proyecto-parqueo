<?php
//dominio,name_user,contraseña,nom_basededatos
$conectador=mysqli_connect("localhost","root","","juan");
// Verificar conexión
// if (!$conectador) {
//     die("❌ Error de conexión: " . mysqli_connect_error());
// } else {
//     echo "✅ Conexión exitosa a la base de datos rudi el mas pro";
// }
//zona-horaria es para que el registro sea exacto
date_default_timezone_set('America/La_Paz');
mysqli_query($conectador,"SET charset 'utf8'");
?>