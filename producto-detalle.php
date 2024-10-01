<?php
session_start();
include 'ver/header.php'; 
include 'login/db.php'; // Conexión a la base de datos

// Obtener el ID del producto desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar el producto desde la base de datos
$query = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Producto no encontrado.";
    exit();
}

// Detalles del producto
$nombre = $product['nombre'];
$imagen_principal = $product['imagen_principal'];
$precio = $product['precio'];
$descripcion = $product['descripcion'];
$colores = json_decode($product['colores'], true);
$tallas = json_decode($product['tallas'], true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nombre); ?> - Detalles</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
     /* Estilos para el slider de imágenes */
     .slider {
            position: relative;
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            border-radius: 8px;
        }
        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
        }
        .slide img {
            width: 100%;
            vertical-align: middle;
            border-radius: 8px;
        }
        .nav-button {
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            border-radius: 0 3px 3px 0;
            user-select: none;
            background-color: rgba(0,0,0,0.5);
            cursor: pointer;
        }
        .prev {
            left: 0;
            border-radius: 3px 0 0 3px;
        }
        .next {
            right: 0;
            border-radius: 3px 3px 3px 3px;
        }
        .product-info {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .color-options select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .size-options label {
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #f44336; /* Color rojo para el botón */
            color: #fff; /* Texto en blanco */
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #c62828;
        }
</style>
<body>

<main>
    <section class="product-details">
        <div class="slider">
            <img id="main-image" src="img/<?php echo htmlspecialchars($imagen_principal); ?>" alt="<?php echo htmlspecialchars($nombre); ?>">
        </div>

        <div class="product-info">
            <h1><?php echo htmlspecialchars($nombre); ?></h1>
            <p>Precio: $<?php echo number_format($precio, 2, ',', '.'); ?></p>
            <p><?php echo htmlspecialchars($descripcion); ?></p>

            <!-- Opciones de color sin imágenes -->
            <div class="product-options">
                <h2>Selecciona el color:</h2>
                <select id="color-select" name="color">
                    <?php if (!empty($colores)) {
                        foreach ($colores as $color) {
                            echo '<option value="' . htmlspecialchars($color) . '">' . htmlspecialchars($color) . '</option>';
                        }
                    } ?>
                </select>

                <!-- Opciones de talla -->
                <div class="size-options">
                    <h2>Selecciona la talla:</h2>
                    <?php if (!empty($tallas)) {
                        foreach ($tallas as $talla) {
                            echo '<label><input type="radio" name="size" value="' . htmlspecialchars($talla) . '" required> ' . htmlspecialchars($talla) . '</label>';
                        }
                    } ?>
                </div>
            </div>

            <!-- Formulario para agregar al carrito -->
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                <input type="hidden" id="selected-color" name="color" value="">
                <input type="hidden" id="selected-size" name="size" value="">
                <button type="submit" class="btn-primary">Agregar al carrito</button>
            </form>
        </div>
    </section>
</main>

<script>
    document.getElementById('color-select').addEventListener('change', function() {
        document.getElementById('selected-color').value = this.value; // Actualizar el valor del color
    });

    document.addEventListener('change', function(e) {
        if (e.target.name === 'size') {
            document.getElementById('selected-size').value = e.target.value; // Actualizar el valor de la talla
        }
    });
</script>

<!-- Footer -->
<?php include 'ver/footer.php'; ?>
</body>
</html>
