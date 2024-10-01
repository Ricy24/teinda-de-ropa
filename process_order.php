<?php
session_start();
include 'db.php'; // Asegúrate de incluir tu archivo de configuración

// Asegúrate de que la función esté definida aquí o incluida
function getCartItems($conn) {
    $cartItems = [];
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $cartItems[] = [
                'id' => $productId,
                'cantidad' => $quantity // Guardamos la cantidad para uso posterior
            ];
        }
    }
    return $cartItems;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $metodo_pago = $_POST['metodo_pago'];

    // Obtener productos del carrito
    $cartItems = getCartItems($conn);

    // Suponiendo que tienes un user_id del usuario logueado
    $userId = $_SESSION['user_id']; // Asegúrate de que este valor exista

    // Insertar el pedido principal en la tabla de pedidos
    $stmt = $conn->prepare("INSERT INTO pedidos (user_id, nombre, direccion, telefono, email, metodo_pago) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $nombre, $direccion, $telefono, $email, $metodo_pago);

    if ($stmt->execute()) {
        $pedidoId = $stmt->insert_id; // Obtener el ID del pedido recién creado

        // Insertar los productos en la tabla de detalles de pedido
        foreach ($cartItems as $item) {
            $productId = $item['id'];
            $cantidad = $item['cantidad'];

            $stmtDetail = $conn->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)");
            $stmtDetail->bind_param("iii", $pedidoId, $productId, $cantidad);
            $stmtDetail->execute();
            $stmtDetail->close();
        }

        // Limpiar el carrito
        $_SESSION['cart'] = []; // Vaciar el carrito

        // Redirigir a thank_you.php
        header("Location: thank_you.php");
        exit(); // Asegúrate de detener la ejecución después de la redirección
    } else {
        echo "Error al procesar el pedido: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
