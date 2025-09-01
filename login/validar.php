<?php
$servername = "localhost";
$username = "root";   // tu usuario de MySQL
$password_db = "";    // tu contraseña de MySQL
$dbname = "parqueo"; // nombre de tu base de datos

$conn = new mysqli($servername, $username, $password_db, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Preparar consulta para evitar inyecciones SQL
$stmt = $conn->prepare("SELECT IdUsuario, Nombre, Contra, Rol FROM Usuario WHERE Nombre = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();

    // Validar contraseña (sin encriptar de momento, compara directo)
    if ($password === $fila['Contra']) {
        session_start();
        $_SESSION['usuario'] = $fila['Nombre'];
        $_SESSION['rol'] = $fila['Rol'];

        echo "<h1>Bienvenido, " . $fila['Nombre'] . " (" . $fila['Rol'] . ")</h1>";
        // Aquí puedes redirigir según el rol
         if ($fila['Rol'] == 'Administrador') {
             header("Location: admin.php");
         } else {
             header("Location: trabajador.php");
         }
         exit();
    } else {
        echo "<h1>Contraseña incorrecta</h1>";
    }
} else {
    echo "<h1>Usuario no encontrado</h1>";
}

$stmt->close();
$conn->close();
?>

