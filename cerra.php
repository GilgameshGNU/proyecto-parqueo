
<?php
// Logica para cerra secion
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
