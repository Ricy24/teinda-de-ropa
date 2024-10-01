<?php
session_start();
include 'login/db.php'; // Asegúrate de que la ruta a tu archivo de conexión a la base de datos sea correcta

// Función para obtener los productos del carrito
function getCartItems($conn) {
    $items = [];
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $item) {
            // Obtén los detalles del producto desde la base de datos
            $query = "SELECT * FROM productos WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            if ($product) {
                $items[] = array_merge($product, $item);
            }
        }
    }
    return $items;
}

// Función para actualizar la cantidad del producto en el carrito
if (isset($_POST['update_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: carrito.php");
    exit();
}

// Función para eliminar un producto del carrito
if (isset($_POST['remove_item'])) {
    $product_id = intval($_POST['product_id']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: carrito.php");
    exit();
}

// Obtener los productos del carrito
$cartItems = getCartItems($conn);

// Calcular el total del carrito
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['precio'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="ver/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .cart {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #eee;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-item-details {
            margin-left: 20px;
            flex: 1;
        }
        .cart-item-details h2 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .cart-item-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .cart-item-details .size-color {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .cart-item-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cart-item-actions button, .cart-item-actions form button {
            background: #f44336;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        .cart-item-actions input {
            width: 50px;
            text-align: center;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .btn-primary {
            display: inline-block;
            background: #f44336;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background: #c62828;
        }
        .cart-total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>

<?php include 'ver/header.php'; ?>

<main>
    <section class="cart">
        <h1>Carrito de Compras</h1>
        <?php if (empty($cartItems)) { ?>
            <p>Tu carrito está vacío.</p>
        <?php } else { ?>
            <form action="carrito.php" method="POST">
                <?php foreach ($cartItems as $item) { ?>
                    <div class="cart-item">
                        <img src="img/<?php echo htmlspecialchars($item['imagen_principal']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                        <div class="cart-item-details">
                            <h2><?php echo htmlspecialchars($item['nombre']); ?></h2>
                            <p>Precio: $<?php echo number_format($item['precio'], 2, ',', '.'); ?></p>
                            <p>Cantidad: <?php echo intval($item['quantity']); ?></p>
                            <p class="size-color">
                                Talla: <?php echo htmlspecialchars($item['size']); ?><br>
                                Color: <?php echo htmlspecialchars($item['color']); ?>
                            </p>
                            <label for="quantity-<?php echo intval($item['id']); ?>">Cantidad:
                                <input type="number" id="quantity-<?php echo intval($item['id']); ?>" name="quantity" value="<?php echo intval($item['quantity']); ?>" min="1">
                            </label>
                            <input type="hidden" name="product_id" value="<?php echo intval($item['id']); ?>">
                        </div>
                        <div class="cart-item-actions">
                            <button type="submit" name="update_cart">Actualizar</button>
                            <form action="carrito.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo intval($item['id']); ?>">
                                <button type="submit" name="remove_item">Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </form>
            <div class="cart-total">
                Total: $<?php echo number_format($total, 2, ',', '.'); ?>
            </div>
            <a href="checkout.php" class="btn-primary">Proceder a la compra</a>
        <?php } ?>
    </section>
</main>

<script>
    function updateCartCount() {
        // Llamada AJAX para obtener el número de productos en el carrito
        fetch('carrito/cart_count.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count;
            })
            .catch(error => console.error('Error:', error));
    }

    // Actualizar el contador del carrito al cargar la página
    window.onload = function() {
        updateCartCount();
    };

    // Actualizar el contador cada 5 segundos (puedes ajustar esto)
    setInterval(updateCartCount, 5000);
</script>

<!-- Footer -->
<?php include 'ver/footer.php'; ?>
</body>
</html>
