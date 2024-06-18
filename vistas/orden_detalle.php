<?php
require_once "./php/main.php";


$conexion= conexion();

// Verificar la conexión
if (mysqli_connect_errno()) {
    echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
    exit();
}
$orden = isset($_GET['orden_id']) ? (int)$_GET['orden_id'] : 0;

if ($orden > 0) {
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
        producto.producto_precio,
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
   
    <title>Detalle de la Compra</title>
    <style>
        .table-rounded {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .table-rounded th:first-child {
            border-top-left-radius: 8px;
        }
        .table-rounded th:last-child {
            border-top-right-radius: 8px;
        }
        .table-rounded tr:last-child td:first-child {
            border-bottom-left-radius: 8px;
        }
        .table-rounded tr:last-child td:last-child {
            border-bottom-right-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container pb-6 pt-6 pl-6 pr-6 mt-6">
<?php include "./inc/btn_back.php"; ?>
    <?php if (!empty($detalle)): ?> 
        <h1 class="title"><strong>Detalle de la Compra #<?php echo htmlspecialchars($detalle[0]['orden_id'], ENT_QUOTES, 'UTF-8'); ?></strong></h1>
        <div class="columns">
            <div class="column">
                <div class="box">
                    <h2 class="subtitle"><strong>Información de la Orden</strong></h2>
                    <p><strong>Orden ID:</strong> <?php echo htmlspecialchars($detalle[0]['orden_id'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Fecha de Orden:</strong> <?php echo htmlspecialchars($detalle[0]['orden_fecha'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Vendedor:</strong> <?php echo htmlspecialchars($detalle[0]['usuario_nombre'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($detalle[0]['usuario_apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Total:</strong> $ <?php echo htmlspecialchars($detalle[0]['total'], ENT_QUOTES, 'UTF-8'); ?> </p>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <h2 class="subtitle"><strong>Información del Cliente</strong></h2>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($detalle[0]['cliente_nombre'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($detalle[0]['cliente_apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($detalle[0]['cliente_email'], ENT_QUOTES, 'UTF-8'); ?></p> 
                </div>
            </div>
        </div>
        
        <div class="box">  
            <h2 class="subtitle "><strong>Detalles de Productos</strong></h2>       
            <table class="table is-fullwidth is-centered table-rounded">
                <thead class="has-background-info has-text-white">
                    <tr>
                        <th>Producto ID</th>
                        <th>Nombre del Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Precio Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['producto_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($item['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>$ <?php echo htmlspecialchars(number_format($item['producto_precio'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>$ <?php echo htmlspecialchars(number_format($item['producto_precio'] * $item['cantidad'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="has-background-grey-light">
                        <td colspan="4" class="has-text-left"><strong>Total de la compra:</strong></td>
                        <td><strong>$ <?php echo htmlspecialchars(number_format($detalle[0]['total'], 2), ENT_QUOTES, 'UTF-8'); ?></strong></td>
                    </tr>
                </tbody>
            </table>
         </div>
    <?php else: ?>
        <div class="notification is-danger">
        <h1 class="title"><strong>Detalle de la Orden no encontrado </h1>
        <p>No se encontró la orden de compra en la Base de Datos.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
