<?php
$isLoggedIn = isset($_SESSION['user_id']); // Verifica si el usuario ha iniciado sesión
$userName = '';

if ($isLoggedIn) {
    include 'login/db.php'; // Conexión a la base de datos

    // Obtén el nombre del usuario desde la base de datos
    $stmt = $conn->prepare('SELECT nombre FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($userName);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Ropa</title>
    <link rel="stylesheet" href="../ver/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilo para el contador en el carrito */
        .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .header-icons {
            position: relative;
        }
        .user-menu {
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px;
            display: none; /* Oculto por defecto */
            z-index: 1000;
        }
        .user-menu a {
            display: block;
            margin: 5px 0;
            text-decoration: none;
            color: black;
        }
        .user-menu a:hover {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php"><img src="ver/logo.png" alt="Logo" class="logo-image"></a>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="productos.php">tienda</a></li>
                <li>
                        <a href="productos.php?category=hombre">Hombre</a>
                        <ul class="sub-menu">
                            <li><a href="#">Camisas</a></li>
                            <li><a href="#">Pantalones</a></li>
                            <li><a href="#">Chaquetas</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="productos.php?category=mujer">Mujer</a>
                        <ul class="sub-menu">
                            <li><a href="#">Vestidos</a></li>
                            <li><a href="#">Blusas</a></li>
                            <li><a href="#">Faldas</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="productos.php?category=niños">Niños</a>
                        <ul class="sub-menu">
                            <li><a href="#">Camisas</a></li>
                            <li><a href="#">Pantalones</a></li>
                            <li><a href="#">Chaquetas</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="search-button">
                <button onclick="openSearch()"><i class="fas fa-search"></i></button>
            </div>
            <div class="header-icons">
                <a href="carrito.php" class="icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count" class="cart-count">0</span>
                </a>
            </div>
            <div class="header-icons">
                <br><br>
                <?php if ($isLoggedIn): ?>
                    <a href="#" class="icon" onclick="toggleUserMenu()"><i class="fas fa-user"></i></a>
                    <div id="user-menu" class="user-menu">
                        <p>Hola, <?php echo htmlspecialchars($userName); ?></p>
                        <a href="login/logout.php">Cerrar Sesión</a>
                    </div>
                <?php else: ?>
                    <a href="login/login.php" class="icon"><i class="fas fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Search Overlay -->
    <div id="search-overlay" class="search-overlay">
        <div class="search-container">
            <button class="closebtn" onclick="closeSearch()">&times;</button>
            <form action="buscar.php" method="GET">
                <input type="text" name="query" placeholder="Buscar productos..." id="search-input">
                <button type="submit" class="search-submit">Buscar</button>
            </form>
        </div>
    </div>

    <script>
        function openSearch() {
            document.getElementById('search-overlay').style.display = 'flex';
        }

        function closeSearch() {
            document.getElementById('search-overlay').style.display = 'none';
        }

        function toggleUserMenu() {
            var menu = document.getElementById('user-menu');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }

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
</body>
</html>
