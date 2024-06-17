<?php
require_once "main.php";

// Establecer la conexión a la base de datos
$conexion = mysqli_connect("host", "usuario", "contraseña", "basededatos");

// Verificar la conexión
if (mysqli_connect_errno()) {
    echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
    exit();
}

// Obtener el ID de la orden de compra desde la URL y sanitizarlo
$orden = isset($_GET['orden']) ? (int)$_GET['orden'] : 0;

if ($orden > 0) {
    // Consulta SQL para obtener la información de la orden de compra y sus detalles
    $stmt = $conexion->prepare("SELECT 
        orden_de_compra.orden_id,
        orden_de_compra.total,
        orden_de_compra.orden_fecha,
        cliente.cliente_nombre,
        cliente.cliente_apellido,
        cliente.cliente_email,
        usuario.usuario_nombre,
        usuario.usuario_apellido,
        detalle_orden_de_compra.detalle_id,
        producto.producto_id,
        producto.producto_nombre,
        detalle_orden_de_compra.cantidad
    FROM 
        orden_de_compra
    JOIN 
        cliente ON orden_de_compra.cliente_id = cliente.cliente_id
    JOIN 
        usuario ON orden_de_compra.vendedor_id = usuario.usuario_id
    JOIN 
        detalle_orden_de_compra ON orden_de_compra.orden_id = detalle_orden_de_compra.orden_id
    JOIN 
        producto ON detalle_orden_de_compra.producto_id = producto.producto_id
    WHERE 
        orden_de_compra.orden_id = ?");
    $stmt->bind_param("i", $orden);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $detalle = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bulma.min.css">
    <title>Detalle de la Compra</title>
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Detalle de la Compra</h1>
            <?php if (!empty($detalle)): ?>
                <div class="box">
                    <h2 class="subtitle">Información de la Orden</h2>
                    <p><strong>Orden ID:</strong> <?php echo htmlspecialchars($detalle[0]['orden_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Fecha de Orden:</strong> <?php echo htmlspecialchars($detalle[0]['orden_fecha'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Total:</strong> <?php echo htmlspecialchars($detalle[0]['total'], ENT_QUOTES, 'UTF-8'); ?> USD</p>
                    <h2 class="subtitle">Información del Cliente</h2>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($detalle[0]['cliente_nombre'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($detalle[0]['cliente_apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($detalle[0]['cliente_email'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <h2 class="subtitle">Información del Vendedor</h2>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($detalle[0]['usuario_nombre'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($detalle[0]['usuario_apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <h2 class="subtitle">Detalles de Productos</h2>
                    <table class="table is-fullwidth">
                        <thead>
                            <tr>
                                <th>Producto ID</th>
                                <th>Nombre del Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalle as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['producto_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($item['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($item['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No se encontró la orden de compra.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
