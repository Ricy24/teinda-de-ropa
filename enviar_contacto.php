<?php
// Conectar a la base de datos
$servername = "localhost"; // Cambia esto si es necesario
$username = "root";        // Cambia esto si es necesario
$password = "";            // Cambia esto si es necesario
$dbname = "tienda";        // Cambia esto si es necesario

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['name'];
$email = $_POST['email'];
$mensaje = $_POST['message'];

// Preparar y ejecutar la consulta
$sql = "INSERT INTO contacto (nombre, email, mensaje) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nombre, $email, $mensaje);

if ($stmt->execute()) {
    echo "<script>
            alert('Mensaje enviado con éxito. Gracias por contactarnos.');
            window.location.href = 'index.php';
          </script>";
} else {
    echo "<script>
            alert('Error al enviar el mensaje. Por favor, inténtalo de nuevo.');
            window.location.href = 'index.php';
          </script>";
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
