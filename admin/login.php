<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el inicio de sesión si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $contrasena = trim($_POST['contrasena']);

    // Consultar el usuario en la base de datos
    $sql = "SELECT contrasena FROM administradores WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($contrasena_hash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($contrasena, $contrasena_hash)) {
            // Guardar el email en la sesión
            $_SESSION['email'] = $email;
            header("Location: admin_productos.php");
            exit();
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        $error_message = "Correo no encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php
    if (isset($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }
    ?>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Correo Electrónico" required><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>

