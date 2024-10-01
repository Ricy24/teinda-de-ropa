<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link rel="stylesheet" href="ver/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <h2>Sobre Nosotros</h2>
                    <p>Somos una tienda de ropa dedicada a ofrecer las últimas tendencias en moda. Encuentra ropa para hombres, mujeres y niños a precios increíbles.</p>
                </div>
                <div class="footer-section links">
                    <h2>Enlaces Rápidos</h2>
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="#">Hombre</a></li>
                        <li><a href="#">Mujer</a></li>
                        <li><a href="#">Niños</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section contact">
                    <h2>Contacto</h2>
                    <p><i class="fas fa-map-marker-alt"></i> Calle Ejemplo 123, Ciudad, País</p>
                    <p><i class="fas fa-phone"></i> +123 456 789</p>
                    <p><i class="fas fa-envelope"></i> info@tiendaropa.com</p>
                    <p><i class="fas fa-envelope"></i> <a href="admin/admin_productos.php">admin</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> Tienda de Ropa. Todos los derechos reservados.</p>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
