<?php
session_start();
include 'ver/header.php'; 
include 'db/db.php'; // Incluye la conexión a la base de datos

// Obtener categorías de la base de datos
$categories_query = "SELECT DISTINCT categoria FROM productos"; // Ajusta 'categoria' si es necesario
$categories_result = mysqli_query($conn, $categories_query);

// Obtener la categoría seleccionada
$selected_category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

// Consulta de productos filtrados por categoría
if ($selected_category) {
    $query = "SELECT * FROM productos WHERE categoria = '$selected_category'";
} else {
    $query = "SELECT * FROM productos";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            width: 250px; /* Tamaño fijo para las tarjetas */
            height: 400px; /* Tamaño fijo para las tarjetas */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .product-card img {
            width: 100%;
            height: 200px; /* Tamaño fijo para la imagen */
            object-fit: cover; /* Asegura que la imagen cubra el área sin distorsionarse */
            transition: opacity 0.3s ease;
        }
        .product-card img:hover {
            opacity: 0.8;
        }
        .product-card h3 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .product-card p {
            font-size: 1.1em;
            color: #333;
        }
        .btn-secondary {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: #fff;
            background-color: #000; /* Cambiado a negro */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #333; /* Color gris oscuro al pasar el mouse */
            transform: scale(1.05);
        }
        .category-filter {
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .category-filter a {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            background-color: #fff;
            color: #333;
            text-decoration: none;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .category-filter a:hover {
            background-color: #f4f4f4;
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .category-filter a.active {
            background-color: #ddd;
            color: #000;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2em;
        }
    </style>
</head>
<body>
    <main>
        <section class="products">
            <div class="container">
                <h1>Nuestros Productos</h1>
                
                <!-- Filtro de categorías -->
                <div class="category-filter">
                    <a href="productos.php" class="<?php echo !$selected_category ? 'active' : ''; ?>">Todos</a>
                    <?php 
                    if ($categories_result) {
                        while ($category = mysqli_fetch_assoc($categories_result)) {
                            $category_name = htmlspecialchars($category['categoria']);
                            $active = ($selected_category == $category_name) ? 'active' : '';
                            echo '<a href="productos.php?category=' . urlencode($category_name) . '" class="' . $active . '">' . $category_name . '</a>';
                        }
                    }
                    ?>
                </div>

                <div class="product-grid">
                    <?php 
                    // Mostrar productos
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Obtener la imagen principal
                            $imagen_principal = isset($row['imagen_principal']) ? htmlspecialchars($row['imagen_principal']) : 'default.jpg';
                            echo '<div class="product-card">';
                            echo '<img src="img/' . $imagen_principal . '" alt="' . htmlspecialchars($row['nombre']) . '">';
                            echo '<div class="product-info">';
                            echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                            echo '<p>$' . number_format($row['precio'], 2, ',', '.') . '</p>';
                            echo '<a href="producto-detalle.php?id=' . htmlspecialchars($row['id']) . '" class="btn-secondary">Ver Detalle</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No hay productos disponibles.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <?php include 'ver/footer.php'; ?>
    </footer>
</body>
</html>
