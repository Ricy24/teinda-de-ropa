<?php
session_start();
include 'login/db.php'; // Asegúrate de que la ruta a tu archivo de conexión a la base de datos es correcta

// Obtener el término de búsqueda
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Evitar inyecciones SQL
$query = mysqli_real_escape_string($conn, $query);

// Realizar la consulta de búsqueda
$sql = "SELECT * FROM productos WHERE nombre LIKE '%$query%' OR descripcion LIKE '%$query%'";
$result = mysqli_query($conn, $sql);

// Verificar si se encontraron resultados
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Estilos para las tarjetas de productos */
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
            width: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100%;
            height: auto;
            display: block;
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
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #333; /* Color gris oscuro al pasar el mouse */
        }
    </style>
</head>
<body>
    <header>
        <?php include 'ver/header.php'; ?>
    </header>

    <main>
        <section class="products">
            <div class="container">
                <h1>Resultados de la búsqueda para '<?php echo htmlspecialchars($query); ?>':</h1>
                <div class="product-grid">
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $imagen_principal = isset($row['imagen_principal']) ? htmlspecialchars($row['imagen_principal']) : 'default.jpg';
                            echo '<div class="product-card">';
                            echo '<img src="img/' . $imagen_principal . '" alt="' . htmlspecialchars($row['nombre']) . '">';
                            echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                            echo '<p>$' . number_format($row['precio'], 2, ',', '.') . '</p>';
                            echo '<a href="producto-detalle.php?id=' . htmlspecialchars($row['id']) . '" class="btn-secondary">Ver Detalle</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No se encontraron resultados para "' . htmlspecialchars($query) . '".</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include 'ver/footer.php'; ?>
    </footer>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
