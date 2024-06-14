<?php
ob_start();

require_once "main.php";
require_once '../libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$orden= 4;//isset($_GET['orden']) ? intval($_GET['orden']) : 0;

if ($orden <= 0) {
    die("Error: El parámetro 'orden' no es válido.");
}

$detalle_query = "SELECT 
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
    orden_de_compra.orden_id = $orden";

$conexion = conexion();

$datos = $conexion->query($detalle_query);
$datos = $datos->fetch_all(MYSQLI_ASSOC);

// Obtenemos los datos generales de la orden
if (!empty($datos)) {
    $orden_detalle = $datos[0]; // Tomamos el primer registro para los datos generales de la orden
    // Calculamos el total de productos
    $total_productos = 0;
    foreach ($datos as $detalle) {
        $total_productos += $detalle['cantidad'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <style>
        title{
            font:Arial;
        }
        .notification-custom {
            background-color: #f0f0f0; /* Color de fondo personalizado */
            padding: 1.5rem; /* Espaciado adicional */
            margin: 0;
            font-size: 1.25rem; /* Tamaño de fuente más grande */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notification-custom p {
            margin-bottom: 0.5rem;
        }
        .table thead {
            background-color: #6a93c7; /* Color de fondo del encabezado de la tabla */
            color: white; /* Color del texto del encabezado */
        }
        .table tfoot {
            background-color: #aeabab; /* Color de fondo del pie de tabla */
            font-weight: bold; /* Texto en negrita */
        }
        logo {
            max-width: 300px;
            margin-bottom: 1rem;
        }
      
    </style>
</head>
<body>
    <section class="hero-body">
        <div class="container">
            <h1 class="title">Detalle de Orden #<?php echo htmlspecialchars($orden); ?></h1>
            <?php if (!empty($orden_detalle)) { ?>
            <div class="notification notification-custom">
                <div>
                    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($orden_detalle['cliente_nombre']) . ' ' . htmlspecialchars($orden_detalle['cliente_apellido']); ?></p>
                    <p><strong>Mail: </strong><?php echo htmlspecialchars($orden_detalle['cliente_email']); ?></p>
                    <p><strong>Vendedor: </strong><?php echo htmlspecialchars($orden_detalle['usuario_nombre']) . ' ' . htmlspecialchars($orden_detalle['usuario_apellido']); ?></p>
                    <p><strong>Total: </strong>$<?php echo htmlspecialchars($orden_detalle['total']); ?></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="notification is-warning">
                No se encontraron detalles para esta orden.
            </div>
            <?php } ?>
        </div>
        <div class="container  pt-6  pb-6">
            <div class="box table-container">
            <h2 class="title">Productos</h2>
                <table class="table is-fullwidth is-striped ">
                    <thead>
                        <tr>
                            <th> ID</th>
                            <th>Nombre del Producto</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($datos)) {
                            foreach ($datos as $detalle) {
                                echo '<tr class="has-text-centered">
                                        <td>' . htmlspecialchars($detalle['producto_id']) . '</td>
                                        <td>' . htmlspecialchars($detalle['producto_nombre']) . '</td>
                                        <td>' . htmlspecialchars($detalle['cantidad']) . '</td>
                                      </tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">No hay detalles para esta orden.</td></tr>';
                        }
                        ?>
                    </tbody>
                    <?php if (!empty($orden_detalle)) { ?>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total de productos</td>
                            <td><?php echo htmlspecialchars($total_productos); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total de la orden</td>
                            <td>$<?php echo htmlspecialchars($orden_detalle['total']); ?></td>
                        </tr>
                    </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
<?php
$html_orden = ob_get_clean();

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html_orden);
$dompdf->setPaper('A4');

$dompdf->render();
$dompdf->stream("archivo_.pdf", array("Attachment" => false));
?>