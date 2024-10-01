<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por tu compra</title>
    <link rel="stylesheet" href="ver/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .thank-you {
            background: #fff;
            padding: 30px;
            margin: 50px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            text-align: center;
        }
        .thank-you h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .thank-you p {
            font-size: 18px;
            margin: 10px 0;
        }
        .thank-you a {
            display: inline-block;
            background: #f44336;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }
        .thank-you a:hover {
            background: #c62828;
        }
    </style>
</head>
<body>

<?php include 'ver/header.php'; ?>

<main>
    <section class="thank-you">
        <h1>¡Gracias por tu compra!</h1>
        <p>Tu pedido ha sido procesado con éxito.</p>

        <a href="index.php">Volver a la tienda</a>
    </section>
</main>

<?php include 'ver/footer.php'; ?>
</body>
</html>
