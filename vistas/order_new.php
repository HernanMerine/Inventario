<?php
require_once "./php/detalle_orden.php";
require_once "./php/envio_mail.php";

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$busqueda = isset($_POST['buscar_producto']) ? $_POST['buscar_producto'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Orden de Compra</title>
    <link rel="stylesheet" href="path/to/bulma.min.css">
</head>
<body>

    <!-- Main Content -->
    <section class="section">
        <div class="container pl-6 pt-6 pr-6 pb-6">
            <h1 class="title">Generar Orden de Compra</h1>
            <div class="columns">
                <!-- Productos -->
                <div class="column is-flex-grow-1">
                    <div class="field">
                        <label class="label">Buscar Producto</label>
                        <div class="control">
                            <form method="post">
                                <input class="input" type="text" name="buscar_producto" placeholder="Escriba aquí el nombre del producto" value="<?php echo htmlspecialchars($busqueda); ?>" required>
                                <button class="button is-info is-rounded is-small" type="submit">Buscar</button>
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
                                    <th>Precio Unidad</th>
                                    <th>Unidades</th>
                                    <th>Agregar a Orden</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $campos = "producto.producto_id,producto.producto_nombre,producto.producto_precio,producto.producto_stock,producto.producto_foto";

                                if (!empty($busqueda)) {
                                    $consulta_datos = "SELECT $campos FROM producto WHERE producto_nombre LIKE '%$busqueda%' ORDER BY producto.producto_nombre ASC";
                                } else {
                                    $consulta_datos = "SELECT $campos FROM producto ORDER BY producto.producto_nombre ASC";
                                }
                                require_once "./php/main.php";
                                $conexion = conexion();

                                $datos = $conexion->query($consulta_datos);
                                $datos = $datos->fetch_all(MYSQLI_ASSOC);

                                foreach ($datos as $producto) {
                                    $imagen = (is_file("./img/producto/" . $producto['producto_foto'])) ? $producto['producto_foto'] : 'producto.jpeg';
                                    $imagen_html = '<img src="./img/producto/' . $imagen . '" style="max-width: 40px;">';
                                    echo '<tr>
                                            <td>' . $imagen_html . '</td>
                                            <td>' . htmlspecialchars($producto['producto_nombre']) . '</td>
                                            <td>' . htmlspecialchars($producto['producto_stock']) . '</td>
                                            <td>$' . number_format($producto['producto_precio'], 2) . '</td>
                                            <td>
                                                <form method="post">
                                                    <input type="hidden" name="product_id" value="' . htmlspecialchars($producto['producto_id']) . '">
                                                    <input type="hidden" name="product_name" value="' . htmlspecialchars($producto['producto_nombre']) . '">
                                                    <input type="hidden" name="price_per_unit" value="' . htmlspecialchars($producto['producto_precio']) . '">
                                                    <select class="select is-rounded is-small" name="units" required>';
                                    
                                    // Generar opciones del desplegable basadas en el stock disponible
                                    for ($i = 1; $i <= $producto['producto_stock']; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                    
                                    echo '</select>
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
                    <h2 class="subtitle">Orden de Compra</h2>
                    <div class="box">
                        <table class="table is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Unidades</th>
                                    <th>Precio Unidad</th>
                                    <th>Total</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalOrden = 0;

                                // Almacenar productos 
                                if (!isset($_SESSION['productosOrden'])) {
                                    $_SESSION['productosOrden'] = array();
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    if (isset($_POST['add_to_order'])) {
                                        $productId = $_POST['product_id'];
                                        $productName = $_POST['product_name'];
                                        $units = intval($_POST['units']);
                                        $pricePerUnit = floatval($_POST['price_per_unit']);

                                        // Subtotal 
                                        $subtotal = $units * $pricePerUnit;

                                        $_SESSION['productosOrden'][] = array(
                                            'id' => $productId,
                                            'nombre' => $productName,
                                            'unidades' => $units,
                                            'precio_unitario' => $pricePerUnit,
                                            'subtotal' => $subtotal,
                                        );
                                    }

                                    if (isset($_POST['remove_from_order'])) {
                                        $index = intval($_POST['index']);
                                        if (isset($_SESSION['productosOrden'][$index])) {
                                            unset($_SESSION['productosOrden'][$index]);
                                            $_SESSION['productosOrden'] = array_values($_SESSION['productosOrden']); 
                                        }
                                    }

                                    if (isset($_POST['send_order']))
                                     {
                                        $clientEmail = htmlspecialchars($_POST['client_email']);
                                        $query = "SELECT * FROM cliente WHERE cliente_email='$clientEmail'";
                                        $check_cliente = $conexion->query($query);

                                        // Cliente está registrado o no
                                        if ($check_cliente->num_rows > 0) 
                                        {
                                            $cliente = $check_cliente->fetch_assoc();
                                            $cliente_id = $cliente['cliente_id']; 
                                            $cliente_nombre = $cliente['cliente_nombre']; 
                                            $cliente_apellido = $cliente['cliente_apellido']; 

                                            foreach ($_SESSION['productosOrden'] as $producto) {
                                                $totalOrden += $producto['subtotal'];
                                            }

                                            $query_insert = "INSERT INTO orden_de_compra (vendedor_id, cliente_id, orden_fecha, total) VALUES ($_SESSION[id], '$cliente_id', NOW(), $totalOrden)";
                                            $result_insert_orden = $conexion->query($query_insert);

                                            if ($result_insert_orden) {
                                                // Obtener el orden_id generado
                                                $orden_id = $conexion->insert_id;

                                                // Insertar los productos de la orden en detalle_orden_de_compra
                                                foreach ($_SESSION['productosOrden'] as $producto) {
                                                    $producto_id = $producto['id'];
                                                    $cantidad = $producto['unidades'];

                                                    $query_insert_detalle = "INSERT INTO detalle_orden_de_compra (orden_id, producto_id, cantidad) VALUES ($orden_id, $producto_id, $cantidad)";
                                                    $result_insert_detalle = $conexion->query($query_insert_detalle);
                                                    }
                                                    // Verificar si la inserción en detalle_orden_de_compra fue exitosa
                                                    if (!$result_insert_detalle) {
                                                        echo '<div class="container mt-6">
                                                                <div class="notification is-danger">
                                                                    <h2 class="subtitle">Error al procesar la Orden de Compra</h2>
                                                                    <p>Ocurrió un error al intentar procesar la orden de compra.</p>
                                                                </div>
                                                            </div>';
                                                        exit; // Salir del script o manejar el error según sea necesario
                                                    }
                                               
                                                foreach ($_SESSION['productosOrden'] as $producto) 
                                                {
                                                    $producto_id = $producto['id'];
                                                    $cantidad = $producto['unidades'];
                                                
                                                $_SESSION['productosOrden'] = array();
                                                 }   
                                                 
                                                 $pdf = generarPDFOrden($orden_id);
                                                $mail= enviarEmailConPDF($clientEmail, $pdf);
                                                    if($mail){
                                                echo '<div class="container mt-6">
                                                        <div class="notification is-success">
                                                            <h2 class="subtitle">Orden de Compra Enviada</h2>
                                                            <p>Cliente: ' . htmlspecialchars($cliente_nombre) . ' ' . htmlspecialchars($cliente_apellido) . '</p>
                                                            <p>Cliente: ' . htmlspecialchars($clientEmail) . '</p>
                                                            <p>Total: $' . number_format($totalOrden, 2) . '</p>
                                                        </div>
                                                    </div>';
                                                    $query_update_stock = "UPDATE producto SET producto_stock = producto_stock - $cantidad WHERE producto_id = $producto_id";
                                                    $result_update_stock = $conexion->query($query_update_stock);
                                                    }
                                                    } else {
                                                    // Manejar el caso en que la inserción de la orden no fue exitosa
                                                    echo '<div class="container mt-6">
                                                            <div class="notification is-danger">
                                                                <h2 class="subtitle">Error al procesar la Orden de Compra</h2>
                                                                <p>Ocurrió un error al intentar procesar la orden de compra.</p>
                                                            </div>
                                                        </div>';
                                                         }
                                            }       else {
                                                // Cliente no registrado, mostrar mensaje de error
                                                echo '<div class="container mt-6">
                                                        <div class="notification is-danger">
                                                            <h2 class="subtitle">Cliente No Registrado</h2>
                                                            <p>El correo electrónico ingresado no corresponde a un cliente registrado.</p>
                                                        </div>
                                                    </div>';
                                            }
                                    }
                                }

                                // Mostrar los productos agregados a la orden
                                if (!empty($_SESSION['productosOrden'])) {
                                    foreach ($_SESSION['productosOrden'] as $index => $producto) {
                                        echo '<tr>
                                                <td>' . htmlspecialchars($producto['nombre']) . '</td>
                                                <td>' . htmlspecialchars($producto['unidades']) . '</td>
                                                <td>$' . number_format($producto['precio_unitario'], 2) . '</td>
                                                <td>$' . number_format($producto['subtotal'], 2) . '</td>
                                                <td>
                                                    <form method="post" style="display:inline;">
                                                        <input type="hidden" name="index" value="' . $index . '">
                                                        <button class="button is-danger is-rounded is-small" type="submit" name="remove_from_order">Eliminar</button>
                                                    </form>
                                                </td>
                                              </tr>';
                                        $totalOrden += $producto['subtotal'];
                                    }
                                }
                                
                                ?>
                            </tbody>
                        </table>

                        <h3 class="title is-4">Total: $<?php echo number_format($totalOrden, 2); ?></h3>
                        <form method="post">
                            <div class="field">
                                <label class="label">Correo Electrónico del Cliente</label>
                                <div class="control">
                                    <input class="input" type="email" name="client_email" placeholder="Ingrese el correo electrónico del cliente" required>
                                </div>
                            </div>

                            <button class="button is-primary" type="submit" name="send_order">Confirmar Orden</button>
                          <a href="./php/detalle_orden.php"> ver pdf</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
