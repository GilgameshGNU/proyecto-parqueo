<?php
$servername = "localhost"; // O 127.0.0.1
$username = "root";
$password = "";
$dbname = "parqueo";

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conectado exitosamente";
?>