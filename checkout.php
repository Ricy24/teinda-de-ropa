<?php
session_start();

// Verifica si el usuario ha iniciado sesión
$isLoggedIn = isset($_SESSION['user_id']);
$userName = '';

if (!$isLoggedIn) {
    header("Location: login/login.php"); // Redirige a la página de login si no ha iniciado sesión
    exit();
} else {
    include 'login/db.php'; // Conexión a la base de datos

    // Obtén el nombre del usuario desde la base de datos
    $stmt = $conn->prepare('SELECT nombre FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();
}

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

// Obtener los productos del carrito
$cartItems = getCartItems($conn);

// Calcular el total del carrito
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['precio'] * $item['quantity'];
}

// Procesar el pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos de envío
    $nombre = htmlspecialchars($_POST['nombre']);
    $direccion = htmlspecialchars($_POST['direccion']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $email = htmlspecialchars($_POST['email']);
    $metodo_pago = htmlspecialchars($_POST['metodo_pago']);

    // Aquí puedes agregar la lógica para guardar el pedido en la base de datos

    // Redirigir a una página de agradecimiento o confirmar el pedido
    header("Location: process_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="ver/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .checkout {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .checkout h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .checkout .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            display: flex;
            align-items: center;
        }
        .checkout .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .checkout .cart-item-details {
            margin-left: 20px;
            flex: 1;
        }
        .checkout .cart-item-details h2 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .checkout .cart-total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }
        .checkout form {
            margin-top: 20px;
        }
        .checkout form input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .checkout .btn-primary {
            display: inline-block;
            background: #f44336;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }
        .checkout .btn-primary:hover {
            background: #c62828;
        }
        .payment-methods {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .payment-methods input[type="radio"] {
            display: none;
        }
        .payment-methods label {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .payment-methods label img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }
        .payment-methods input[type="radio"]:checked + label {
            border-color: #f44336;
            background: #f9f9f9;
        }
    </style>
</head>
<body>

<?php include 'ver/header.php'; ?>

<main>
    <section class="checkout">
        <h1>Revisa tu pedido</h1>
        <?php if (empty($cartItems)) { ?>
            <p>Tu carrito está vacío.</p>
        <?php } else { ?>
            <?php foreach ($cartItems as $item) { ?>
                <div class="cart-item">
                    <img src="img/<?php echo htmlspecialchars($item['imagen_principal']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                    <div class="cart-item-details">
                        <h2><?php echo htmlspecialchars($item['nombre']); ?></h2>
                        <p>Precio: $<?php echo number_format($item['precio'], 2, ',', '.'); ?></p>
                        <p>Cantidad: <?php echo intval($item['quantity']); ?></p>
                    </div>
                </div>
            <?php } ?>
            <div class="cart-total">
                Total: $<?php echo number_format($total, 2, ',', '.'); ?>
            </div>

            <h2>Información de envío</h2>
            <form action="process_order.php" method="POST">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="text" name="direccion" placeholder="Dirección de envío" required>
    <input type="text" name="telefono" placeholder="Teléfono" required>
    <input type="email" name="email" placeholder="Email" required>

    <h2>Método de pago</h2>
    <div class="payment-methods">
        <input type="radio" id="daviplata" name="metodo_pago" value="Daviplata" required>
        <label for="daviplata">
            <img src="img/daviplata-logo.png" alt="Daviplata"> Daviplata
        </label>
        
        <input type="radio" id="nequi" name="metodo_pago" value="Nequi" required>
        <label for="nequi">
            <img src="img/nequi-logo.png" alt="Nequi"> Nequi
        </label>
    </div>

    <button type="submit" class="btn-primary">Finalizar compra</button>
</form>

        <?php } ?>
    </section>
</main>

<!-- Footer -->
<?php include 'ver/footer.php'; ?>
</body>
</html>
