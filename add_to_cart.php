<?php
session_start();
include 'db/db.php'; // Conexión a la base de datos

// Verificar que los datos requeridos estén presentes
if (isset($_POST['product_id']) && isset($_POST['size']) && isset($_POST['color'])) {
    // Sanitizar y validar los datos recibidos
    $product_id = intval($_POST['product_id']);
    $size = htmlspecialchars($_POST['size']);
    $color = htmlspecialchars($_POST['color']);

    // Validar que el ID del producto es positivo
    if ($product_id <= 0) {
        header('Location: productos.php?error=invalid_product_id');
        exit();
    }

    // Validar que el tamaño y color no estén vacíos
    if (empty($size) || empty($color)) {
        header('Location: productos.php?error=invalid_size_or_color');
        exit();
    }

    // Verificar si el carrito ya está inicializado en la sesión
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Verificar si el producto ya está en el carrito
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'size' => $size,
            'color' => $color,
            'quantity' => 1 // Puedes ajustar esto según tu lógica
        );
    } else {
        // Incrementar la cantidad si ya existe
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    }

    // Redirigir a la página del producto con un mensaje de éxito (opcional)
    header('Location: producto-detalle.php?id=' . $product_id . '&added=success');
    exit();
} else {
    // Redirigir si faltan datos
    header('Location: productos.php?error=missing_data');
    exit();
}
