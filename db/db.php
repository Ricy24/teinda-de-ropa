<?php
// db.php
$servername = "localhost";
$username = "root";  // Ajusta según tu configuración
$password = "";      // Ajusta según tu configuración
$dbname = "tienda";  // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
