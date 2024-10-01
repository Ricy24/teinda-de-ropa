<?php
include 'db.php';

session_start();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se ha proporcionado el ID
if (!isset($_GET['id'])) {
    header('Location: admin_productos.php');
    exit();
}

$id = intval($_GET['id']);

// Obtener el usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: admin_productos.php');
    exit();
}

$usuario = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Actualizar el usuario
    $sql = "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $nombre, $email, $password, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensaje = 'Usuario actualizado exitosamente.';
    } else {
        $mensaje = 'Error al actualizar el usuario.';
    }
    $stmt->close();

    // Redirigir después de la actualización
    header('Location: admin_productos.php');
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        
        <?php if (isset($mensaje)) : ?>
            <div class="message">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="editar_usuario.php?id=<?php echo $id; ?>" method="POST">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            <input type="password" name="password" placeholder="Nueva contraseña (opcional)">
            <button type="submit">Actualizar Usuario</button>
        </form>

        <a href="admin_productos.php">Volver a la administración</a>
    </div>
</body>
</html>
