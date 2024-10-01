<?php
// Incluir la conexión a la base de datos
include 'db.php';

session_start();

// Verificar si el usuario está autenticado como administrador
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Manejar la eliminación de un usuario
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $mensaje = 'Usuario eliminado exitosamente.';
    } else {
        $mensaje = 'Error al eliminar el usuario.';
    }
    $stmt->close();
}

// Obtener todos los usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Obtener todos los productos
$sql_productos = "SELECT * FROM productos";
$result_productos = $conn->query($sql_productos);

$productos = [];
if ($result_productos->num_rows > 0) {
    while ($row_producto = $result_productos->fetch_assoc()) {
        $productos[] = $row_producto;
    }
}

// Obtener todos los pedidos
$sql_pedidos = "SELECT * FROM pedidos";
$result_pedidos = $conn->query($sql_pedidos);

$pedidos = [];
if ($result_pedidos->num_rows > 0) {
    while ($row_pedido = $result_pedidos->fetch_assoc()) {
        $pedidos[] = $row_pedido;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios, Productos y Pedidos</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user icon"></i> Administración de Usuarios</h1>
        
        <?php if (isset($mensaje)) : ?>
            <div class="message <?php echo isset($error) ? 'error' : ''; ?>">
                <i class="fas fa-info-circle icon"></i>
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>



        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn-edit"><i class="fas fa-edit icon"></i> Editar</a>
                            <a href="admin_productos.php?delete=<?php echo $usuario['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');"><i class="fas fa-trash icon"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1><i class="fas fa-box icon"></i> Administración de Productos</h1>

        <a href="agregar_producto.php" class="contact-btn"><i class="fas fa-plus-circle icon"></i> Agregar Producto</a>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Imagen Principal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                        <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                        <td><img src="../img/<?php echo htmlspecialchars($producto['imagen_principal']); ?>" alt="Imagen" width="100"></td>
                        <td>
                            <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn-edit"><i class="fas fa-edit icon"></i> Editar</a>
                            <a href="admin_productos.php?delete=<?php echo $producto['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');"><i class="fas fa-trash icon"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1><i class="fas fa-receipt icon"></i> Administración de Pedidos</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario ID</th>
                    <th>Total</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Método de Pago</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['total']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['email']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['metodo_pago']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>
                        <td>
                            
                            <a href="admin_productos.php?delete=<?php echo $pedido['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este pedido?');"><i class="fas fa-trash icon"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../index.php"><i class="fas fa-home icon"></i> Volver al Inicio</a>
        <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>

    </div>
</body>
</html>
