<?php
require_once "./php/envio_mail_prov.php";
require_once './libreria/dompdf/autoload.inc.php'; // Include Dompdf autoloader

use Dompdf\Dompdf; // Import Dompdf namespace

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$proveedor_id = isset($_POST['proveedor_id']) ? $_POST['proveedor_id'] : (isset($_SESSION['proveedor_id']) ? $_SESSION['proveedor_id'] : '');
$proveedor_email = '';

// Obtener los proveedores
require_once "./php/main.php";
$conexion = conexion();
$query_proveedores = "SELECT proveedor_id, proveedor_nombre, proveedor_mail FROM proveedor ORDER BY proveedor_nombre ASC";
$proveedores = $conexion->query($query_proveedores);
$proveedores = $proveedores->fetch_all(MYSQLI_ASSOC);

if (!empty($proveedor_id)) {
    // Guardar el proveedor seleccionado en la sesión
    $_SESSION['proveedor_id'] = $proveedor_id;

    // Obtener el email del proveedor seleccionado
    foreach ($proveedores as $proveedor) {
        if ($proveedor['proveedor_id'] == $proveedor_id) {
            $proveedor_email = $proveedor['proveedor_mail'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Pedido a Proveedor </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
</head>
<body>

<!-- Main Content -->
<section class="section">
    <div class="container pl-6 pt-6 pr-6 pb-6">
        <h1 class="title">Generar Pedido a Proveedor</h1>
        <div class="columns">
            <!-- Productos -->
            <div class="column is-flex-grow-1">
                <div class="field">
                    <label class="label">Seleccionar Proveedor</label>
                    <div class="control">
                        <form method="post">
                            <div class="select">
                                <select name="proveedor_id" onchange="this.form.submit()" required>
                                    <option value="">Seleccione un proveedor</option>
                                    <?php
                                    foreach ($proveedores as $proveedor) {
                                        echo '<option value="' . htmlspecialchars($proveedor['proveedor_id']) . '"' . ($proveedor_id == $proveedor['proveedor_id'] ? ' selected' : '') . '>' . htmlspecialchars($proveedor['proveedor_nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box" id="product-list">
                    <!-- Tabla dinámica de productos -->
                    <table class="table is-fullwidth">
                        <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Unidades</th>
                            <th>Agregar a Orden</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $campos = "producto.producto_id,producto.producto_nombre,producto.producto_stock,producto.producto_foto";
                        $consulta_datos = "SELECT $campos FROM producto WHERE proveedor_id='$proveedor_id' ORDER BY producto.producto_nombre ASC";

                        $datos = $conexion->query($consulta_datos);
                        $datos = $datos->fetch_all(MYSQLI_ASSOC);

                        foreach ($datos as $producto) {
                            $imagen = (is_file("./img/producto/" . $producto['producto_foto'])) ? $producto['producto_foto'] : 'producto.jpeg';
                            $imagen_html = '<img src="./img/producto/' . $imagen . '" style="max-width: 40px;">';
                            echo '<tr>
                                    <td>' . $imagen_html . '</td>
                                    <td>' . htmlspecialchars($producto['producto_nombre']) . '</td>
                                    <td>' . htmlspecialchars($producto['producto_stock']) . '</td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="product_id" value="' . htmlspecialchars($producto['producto_id']) . '">
                                            <input type="hidden" name="product_name" value="' . htmlspecialchars($producto['producto_nombre']) . '">
                                            <input type="number" class="input is-rounded is-small" name="units" min="1" max="' . htmlspecialchars($producto['producto_stock']) . '" required>
                                    </td>
                                    <td>
                                        <button class="button is-primary is-rounded is-small add-to-order" type="submit" name="add_to_order">Agregar</button>
                                    </td>
                                  </form>
                                </tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Orden de Compra -->
            <div class="column is-flex-grow-1">
                <h2 class="subtitle"><strong>Detalle de Pedido</strong></h2>
                <div class="box">
                    <table class="table is-fullwidth">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Unidades</th>
                            <th>Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalOrden = 0;

                        if (!isset($_SESSION['productosOrden'])) {
                            $_SESSION['productosOrden'] = array();
                        }

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($_POST['add_to_order'])) {
                                $productId = $_POST['product_id'];
                                $productName = $_POST['product_name'];
                                $units = intval($_POST['units']);

                                $_SESSION['productosOrden'][] = array(
                                    'id' => $productId,
                                    'nombre' => $productName,
                                    'unidades' => $units,
                                );
                            }

                            if (isset($_POST['remove_from_order'])) {
                                $index = intval($_POST['index']);
                                if (isset($_SESSION['productosOrden'][$index])) {
                                    unset($_SESSION['productosOrden'][$index]);
                                    $_SESSION['productosOrden'] = array_values($_SESSION['productosOrden']);
                                }
                            }

                            if (isset($_POST['send_order'])) {
                                $clientEmail = htmlspecialchars($_POST['client_email']);
                                $query = "SELECT * FROM proveedor WHERE proveedor_mail='$clientEmail'";
                                $check_proveedor = $conexion->query($query);

                                if ($check_proveedor->num_rows > 0) {
                                    $proveedor = $check_proveedor->fetch_assoc();
                                    $proveedor_nombre = $proveedor['proveedor_nombre'];

                                    // Generar PDF
                                    $pdf = generarPDFPedidoProv($_SESSION['productosOrden'], $proveedor_nombre, $clientEmail);
                                    $mail = enviarEmailPedidoConPDF($clientEmail, $pdf, $proveedor_nombre);

                                    if ($mail) {
                                        echo '<div class="container mt-6">
                                                <div class="notification is-success">
                                                    <h2 class="subtitle">Pedido Enviado</h2>
                                                    <p>Proveedor: ' . htmlspecialchars($proveedor_nombre) . '</p>
                                                    <p>Correo: ' . htmlspecialchars($clientEmail) . '</p>
                                                </div>
                                            </div>';

                                        // Vaciar la orden después de enviarla
                                        $_SESSION['productosOrden'] = array();
                                    } else {
                                        echo '<div class="container mt-6">
                                                <div class="notification is-danger">
                                                    <h2 class="subtitle">Error al enviar el Pedido</h2>
                                                    <p>Ocurrió un error al intentar enviar el pedido.</p>
                                                </div>
                                            </div>';
                                    }
                                } else {
                                    echo '<div class="container mt-6">
                                            <div class="notification is-danger">
                                                <h2 class="subtitle">Proveedor No Registrado</h2>
                                                <p>El correo electrónico ingresado no corresponde a un proveedor registrado.</p>
                                            </div>
                                        </div>';
                                }
                            }

                            if (isset($_POST['empty_order'])) {
                                $_SESSION['productosOrden'] = array();
                            }
                        }

                        if (!empty($_SESSION['productosOrden'])) {
                            foreach ($_SESSION['productosOrden'] as $index => $producto) {
                                echo '<tr>
                                        <td>' . htmlspecialchars($producto['nombre']) . '</td>
                                        <td>' . htmlspecialchars($producto['unidades']) . '</td>
                                        <td>
                                            <form method="post">
                                                <input type="hidden" name="index" value="' . $index . '">
                                                <button class="button is-danger is-rounded is-small" type="submit" name="remove_from_order">Eliminar</button>
                                            </form>
                                        </td>
                                      </tr>';
                                $totalOrden += $producto['unidades'];
                            }
                        } else {
                            echo '<tr><td colspan="3">No hay productos en la orden.</td></tr>';
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="2">Total de productos</td>
                            <td><?php echo htmlspecialchars($totalOrden); ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Formulario de Envío de Orden -->
                <div class="box">
                    <form method="post">
                        <div class="field">
                            <label class="label">Correo Electrónico del Proveedor</label>
                            <div class="control">
                                <input class="input" type="email" name="client_email" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($proveedor_email); ?>" required>
                            </div>
                        </div>
                        <div class="buttons mt-4">
                            <button class="button is-success is-rounded" type="submit" name="send_order">Enviar Pedido</button>
                            <button class="button is-danger is-rounded" type="submit" name="empty_order">Vaciar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selects = Array.from(document.querySelectorAll('select'));
        selects.forEach(select => {
            select.addEventListener('change', () => {
                select.parentNode.submit();
            });
        });
    });
</script>

</body>
</html>


<?php
function generarPDFPedidoProv($productosOrden, $proveedor_nombre, $clientEmail) {
    ob_start();

    $total_productos = 0;
    foreach ($productosOrden as $producto) {
        $total_productos += $producto['unidades'];
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <style>
            title {
                font: Arial;
            }
            .notification-custom {
                background-color: #f0f0f0;
                padding: 1.5rem;
                margin: 0;
                font-size: 1.25rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .notification-custom p {
                margin-bottom: 0.5rem;
            }
            .table thead {
                background-color: #6a93c7;
                color: white;
            }
            .table tfoot {
                background-color: #aeabab;
                font-weight: bold;
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
            <h1 class="title">Detalle de Pedido</h1>
            <div class="notification notification-custom">
                <div>
                    <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($proveedor_nombre); ?></p>
                    <p><strong>Correo: </strong><?php echo htmlspecialchars($clientEmail); ?></p>
                </div>
            </div>
        </div>
        <div class="container pt-6 pb-6">
            <div class="box table-container">
                <h2 class="title">Productos</h2>
                <table class="table is-fullwidth is-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Producto</th>
                        <th>Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($productosOrden as $producto) {
                        echo '<tr class="has-text-centered">
                                <td>' . htmlspecialchars($producto['id']) . '</td>
                                <td>' . htmlspecialchars($producto['nombre']) . '</td>
                                <td>' . htmlspecialchars($producto['unidades']) . '</td>
                              </tr>';
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2">Total de productos</td>
                        <td><?php echo htmlspecialchars($total_productos); ?></td>
                    </tr>
                    </tfoot>
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
    return $dompdf->output();
}
?>
