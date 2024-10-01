<?php
session_start();

function getCartCount() {
    // Suponiendo que el carrito se guarda en la sesión
    return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
}

header('Content-Type: application/json');
echo json_encode(['count' => getCartCount()]);
