<?php
include 'db.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Obtener el producto actual
$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();
$stmt->close();

// Manejar la actualización del producto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $imagen_principal = $producto['imagen_principal'];
    $colores = json_encode($_POST['colores']); // Guardar colores como JSON
    $tallas = json_encode($_POST['tallas']); // Guardar tallas como JSON

    // Si se sube una nueva imagen, actualizarla
    if (!empty($_FILES['imagen_principal']['name'])) {
        $imagen_principal = $_FILES['imagen_principal']['name'];
        // Cambia la ruta para guardar la imagen en la carpeta img
        move_uploaded_file($_FILES['imagen_principal']['tmp_name'], "../img/" . $imagen_principal);
    }

    $sql = "UPDATE productos SET nombre = ?, precio = ?, descripcion = ?, categoria = ?, imagen_principal = ?, colores = ?, tallas = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdsssssi', $nombre, $precio, $descripcion, $categoria, $imagen_principal, $colores, $tallas, $id);

    if ($stmt->execute()) {
        $mensaje = 'Producto actualizado exitosamente.';
    } else {
        $mensaje = 'Error al actualizar el producto.';
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
    <title>Editar Producto</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .dynamic-inputs {
            margin-bottom: 10px;
        }
    </style>
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
</head>
<body>
    <div class="container">
        <h1>Editar Producto</h1>

        <?php if (isset($mensaje)) : ?>
            <div class="message">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="editar_producto.php?id=<?php echo $producto['id']; ?>" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

            <label for="categoria">Categoría:</label>
            <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>

            <label for="imagen_principal">Imagen Principal:</label>
            <input type="file" name="imagen_principal">
            <img src="../img/<?php echo htmlspecialchars($producto['imagen_principal']); ?>" alt="Imagen" width="100">

            <label for="colores">Colores:</label>
            <div id="colores-container">
                <?php
                $colores = json_decode($producto['colores'], true);
                foreach ($colores as $color) {
                    echo '<input type="text" name="colores[]" value="' . htmlspecialchars($color) . '" class="dynamic-inputs">';
                }
                ?>
            </div>
            <button type="button" onclick="agregarColor()">Agregar Color</button>

            <label for="tallas">Tallas:</label>
            <div id="tallas-container">
                <?php
                $tallas = json_decode($producto['tallas'], true);
                foreach ($tallas as $talla) {
                    echo '<input type="text" name="tallas[]" value="' . htmlspecialchars($talla) . '" class="dynamic-inputs">';
                }
                ?>
            </div>
            <button type="button" onclick="agregarTalla()">Agregar Talla</button>

            <button type="submit">Actualizar Producto</button>
        </form>

        <a href="admin_productos.php"><i class="fas fa-arrow-left icon"></i> Volver</a>
    </div>
</body>
</html>
