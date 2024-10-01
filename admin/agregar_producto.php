<?php
include 'db.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $imagen_principal = $_FILES['imagen_principal']['name'];
    
    // Guardar imagen principal en la carpeta img
    $ruta_imagen = "../img/" . $imagen_principal; // Ajusta la ruta para la carpeta img
    move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_imagen);

    // Guardar colores y tallas como JSON
    $colores = json_encode($_POST['colores']); 
    $tallas = json_encode($_POST['tallas']); 

    // Insertar producto en la base de datos
    $sql = "INSERT INTO productos (nombre, precio, descripcion, categoria, imagen_principal, colores, tallas) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdsssss', $nombre, $precio, $descripcion, $categoria, $imagen_principal, $colores, $tallas);

    if ($stmt->execute()) {
        $mensaje = 'Producto agregado exitosamente.';
    } else {
        $mensaje = 'Error al agregar el producto.';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        function agregarColor() {
            const contenedor = document.getElementById('colores-container');
            const nuevoColor = document.createElement('input');
            nuevoColor.type = 'text';
            nuevoColor.name = 'colores[]';
            nuevoColor.placeholder = 'Color';
            nuevoColor.classList.add('dynamic-inputs');
            contenedor.appendChild(nuevoColor);
        }

        function agregarTalla() {
            const contenedor = document.getElementById('tallas-container');
            const nuevaTalla = document.createElement('input');
            nuevaTalla.type = 'text';
            nuevaTalla.name = 'tallas[]';
            nuevaTalla.placeholder = 'Talla';
            nuevaTalla.classList.add('dynamic-inputs');
            contenedor.appendChild(nuevaTalla);
        }
    </script>
    <style>
        .dynamic-inputs {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Producto</h1>

        <?php if (isset($mensaje)) : ?>
            <div class="message">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="agregar_producto.php" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" name="nombre" required>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required></textarea>

            <label for="categoria">Categoría:</label>
            <input type="text" name="categoria" required>

            <label for="imagen_principal">Imagen Principal:</label>
            <input type="file" name="imagen_principal" required>

            <label for="colores">Colores:</label>
            <div id="colores-container">
                <input type="text" name="colores[]" placeholder="Color" class="dynamic-inputs">
            </div>
            <button type="button" onclick="agregarColor()">Agregar Color</button>

            <label for="tallas">Tallas:</label>
            <div id="tallas-container">
                <input type="text" name="tallas[]" placeholder="Talla" class="dynamic-inputs">
            </div>
            <button type="button" onclick="agregarTalla()">Agregar Talla</button>

            <button type="submit">Agregar Producto</button>
        </form>

        <a href="admin_productos.php"><i class="fas fa-arrow-left icon"></i> Volver</a>
    </div>
</body>
</html>
