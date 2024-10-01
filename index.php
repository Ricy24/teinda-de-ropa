<?php
session_start();
?>

<?php include 'ver/header.php';?>
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Contact Us Section */
    .contact-us {
        background-color: #f5f5f5;
        padding: 50px 20px;
    }

    .contact-us h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.5em;
    }

    .contact-form {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .form-group textarea {
        resize: vertical;
    }

    .contact-form button {
        display: block;
        width: 100%;
        background-color: #000;
        color: #fff;
        padding: 15px;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .contact-form button:hover {
        background-color: #333;
    }

    /* About Us Section */
    .about-us {
        background-color: #fff;
        padding: 50px 20px;
    }

    .about-content {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .about-info {
        flex: 1;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .about-info h2 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #333;
    }

    .about-info p {
        font-size: 16px;
        color: #666;
        line-height: 1.6;
    }

    .about-icons {
        flex: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .about-icon {
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .about-icon i {
        font-size: 2em;
        color: #333;
        margin-bottom: 10px;
    }

    .about-icon h3 {
        margin: 10px 0;
        font-size: 1.2em;
    }

    .about-icon p {
        font-size: 1em;
        color: #666;
    }

    .about-icon:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Our Team Section */
    .our-team {
        background-color: #f5f5f5;
        padding: 50px 20px;
    }

    .our-team h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.5em;
    }

    .team-grid {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .team-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        flex: 1;
        max-width: 300px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .team-card img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-bottom: 15px;
    }

    .team-card h3 {
        font-size: 1.5em;
        margin: 10px 0;
    }

    .team-card p {
        font-size: 1em;
        color: #666;
    }

    .team-card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    /* Pantalla de Carga */
    .loader {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .jimu-primary-loading:before,
    .jimu-primary-loading:after {
        position: absolute;
        top: 0;
        content: '';
    }

    .jimu-primary-loading:before {
        left: -19.992px;
    }

    .jimu-primary-loading:after {
        left: 19.992px;
        -webkit-animation-delay: 0.32s !important;
        animation-delay: 0.32s !important;
    }

    .jimu-primary-loading:before,
    .jimu-primary-loading:after,
    .jimu-primary-loading {
        background: #076fe5;
        -webkit-animation: loading-keys-app-loading 0.8s infinite ease-in-out;
        animation: loading-keys-app-loading 0.8s infinite ease-in-out;
        width: 13.6px;
        height: 32px;
    }

    .jimu-primary-loading {
        text-indent: -9999em;
        margin: auto;
        position: absolute;
        right: calc(50% - 6.8px);
        top: calc(50% - 16px);
        -webkit-animation-delay: 0.16s !important;
        animation-delay: 0.16s !important;
    }

    @-webkit-keyframes loading-keys-app-loading {
        0%, 80%, 100% {
            opacity: .75;
            box-shadow: 0 0 #076fe5;
            height: 32px;
        }
        40% {
            opacity: 1;
            box-shadow: 0 -8px #076fe5;
            height: 40px;
        }
    }

    @keyframes loading-keys-app-loading {
        0%, 80%, 100% {
            opacity: .75;
            box-shadow: 0 0 #076fe5;
            height: 32px;
        }
        40% {
            opacity: 1;
            box-shadow: 0 -8px #076fe5;
            height: 40px;
        }
    }
      /* Contact Us Section */
      .contact-us {
        background-color: #f5f5f5;
        padding: 50px 20px;
    }

    .contact-us h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 2.5em;
    }

    .contact-form {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .form-group textarea {
        resize: vertical;
    }

    .contact-form button {
        display: block;
        width: 100%;
        background-color: #000;
        color: #fff;
        padding: 15px;
        border: none;
        border-radius: 4px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .contact-form button:hover {
        background-color: #333;
    }
</style>

<!-- Pantalla de Carga -->
<div class="loader">
    <div class="justify-content-center jimu-primary-loading"></div>
</div>

<!-- Main Content -->
<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenido a Nuestra Tienda de Ropa</h1>
            <p>Encuentra las últimas tendencias en moda para ti.</p>
            <a href="productos.php" class="btn-primary">Ver Productos</a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <h2>Productos Destacados</h2>
            <div class="product-grid">
                <!-- Placeholder for Featured Products -->
                <div class="product-card">
                    <img src="img/camisa-formal-1.jpg" alt="Producto 1">
                    <h3>Camisa formal</h3>
                    <a href="producto-detalle.php?id=7" class="btn-secondary">Ver Detalles</a>
                </div>
                <div class="product-card">
                    <img src="img/camiseta-divertida.jpg" alt="Producto 2">
                    <h3>Camiseta divertida</h3>
                    <a href="producto-detalle.php?id=8" class="btn-secondary">Ver Detalles</a>
                </div>
                <div class="product-card">
                    <img src="img/chaqueta-azul.jpg" alt="Producto 3">
                    <h3>Chaqueta azul</h3>
                    <a href="producto-detalle.php?id=9" class="btn-secondary">Ver Detalles</a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-us">
        <div class="container about-content">
            <div class="about-info">
                <h2>Acerca de Nosotros</h2>
                <p>En nuestra tienda, estamos comprometidos con ofrecerte la mejor ropa con estilo y calidad. Nuestro equipo trabaja arduamente para encontrar las últimas tendencias y ofrecerte una experiencia de compra excepcional.</p>
                <div class="about-icons">
                    <div class="about-icon">
                        <i class="fas fa-truck"></i>
                        <h3>Envío Rápido</h3>
                        <p>Recibe tu pedido en tiempo récord.</p>
                    </div>
                    <div class="about-icon">
                        <i class="fas fa-credit-card"></i>
                        <h3>Pagos Seguros</h3>
                        <p>Compra con confianza utilizando nuestros métodos de pago seguros.</p>
                    </div>
                    <div class="about-icon">
                        <i class="fas fa-undo"></i>
                        <h3>Devoluciones Fáciles</h3>
                        <p>Devoluciones sin complicaciones para tu tranquilidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    <section class="our-team">
        <div class="container">
            <h2>Conoce a Nuestro Equipo</h2>
            <div class="team-grid">
                <div class="team-card">
                    <img src="img/garzon.jpg" alt="Garzón">
                    <h3>Garzón</h3>
                    <p>Experto en moda masculina, siempre a la vanguardia de las últimas tendencias.</p>
                </div>
                <div class="team-card">
                    <img src="img/maikol.jpg" alt="Maikol">
                    <h3>Maikol</h3>
                    <p>Especialista en accesorios y complementos para cada ocasión.</p>
                </div>
            </div>
        </div>
    </section>

<!-- Contact Us Section -->
<section class="contact-us">
    <div class="container">
        <h2>Contacto</h2>
        <form action="enviar_contacto.php" method="post" class="contact-form">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Mensaje</label>
                <textarea id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Enviar</button>
        </form>
    </div>
</section>
</main>

<!-- Footer -->
<?php include 'ver/footer.php';?>

<!-- Scripts -->
<script>
    // Mostrar la pantalla de carga durante 3 segundos
    window.addEventListener('load', function () {
        setTimeout(function () {
            document.querySelector('.loader').style.display = 'none';
        }, 2000); 
    });
</script>
</body>
</html>
