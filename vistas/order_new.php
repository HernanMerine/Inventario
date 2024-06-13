
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
                    <h2 class="subtitle">Listado de Productos</h2>
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
                               
                               require_once "./php/main.php";
                                $campos = "producto.producto_id,producto.producto_nombre,producto.producto_precio,producto.producto_stock,producto.producto_foto";

                                if (isset($busqueda) && $busqueda != "") {
                                    $consulta_datos = "SELECT $campos FROM producto LIKE '%$busqueda%' ORDER BY producto.producto_nombre asc";
                                    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_nombre LIKE '%$busqueda%'";
                                } else {
                                    $consulta_datos = "SELECT $campos FROM producto ORDER BY producto.producto_nombre ASC ";
                                    $consulta_total = "SELECT COUNT(producto_id) FROM producto";
                                }
                                
                                $conexion = conexion();
                                
                                $datos = $conexion->query($consulta_datos);
                                $datos = $datos->fetch_all(MYSQLI_ASSOC);
                                

                                foreach ($datos as $producto) {
                                    $imagen = (is_file("./img/producto/" . $producto['producto_foto'])) ? $producto['producto_foto'] : 'producto.jpeg';
                                    $imagen_html = '<img src="./img/producto/' . $imagen . '" style="max-width: 40px;">';
                                    echo '<tr>
                                            <td>'.$imagen_html. '</td>
                                            <td>' . $producto['producto_nombre'] . '</td> 
                                            <td>$' . number_format($producto['producto_precio'], 2) . '</td>
                                             <td>' . $producto['producto_stock'] . '</td>
                                            <td>
                                                <form method="post">
                                                    <input type="hidden" name="product_id" value="' . $producto['producto_id'] . '">
                                                    <input class="input" type="number" min="1" name="units" placeholder="Unidades" required>
                                                    <input type="hidden" name="product_name" value="' . $producto['producto_nombre'] . '">
                                                    <input type="hidden" name="price_per_unit" value="' . $producto['producto_precio'] . '">
                                                </td>
                                                <td> 
                                                    <button class="button is-primary" type="submit" name="add_to_order">Agregar</button>
                                                </form>
                                            </td>
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

                                // Inicializamos un array para almacenar los productos agregados
                                if (!isset($_SESSION['productosOrden'])) {
                                    $_SESSION['productosOrden'] = array();
                                }

                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    if (isset($_POST['add_to_order'])) {
                                        // Obtener datos del producto enviado por el formulario
                                        $productId = $_POST['product_id'];
                                        $productName = $_POST['product_name'];
                                        $units = intval($_POST['units']);
                                        $pricePerUnit = floatval($_POST['price_per_unit']);

                                        // Calcular subtotal para este producto
                                        $subtotal = $units * $pricePerUnit;

                                        // Agregar el producto a la lista de productos de la orden
                                        $_SESSION['productosOrden'][] = array(
                                            'id' => $productId,
                                            'nombre' => $productName,
                                            'unidades' => $units,
                                            'precio_unitario' => $pricePerUnit,
                                            'subtotal' => $subtotal,
                                        );
                                    }

                                    if (isset($_POST['remove_from_order'])) {
                                        // Obtener el índice del producto a eliminar
                                        $index = intval($_POST['index']);
                                        if (isset($_SESSION['productosOrden'][$index])) {
                                            unset($_SESSION['productosOrden'][$index]);
                                            $_SESSION['productosOrden'] = array_values($_SESSION['productosOrden']); // Reindexar el array
                                        }
                                    }

                                    if (isset($_POST['send_order'])) {
                                        $clientName = htmlspecialchars($_POST['client_name']);

                                        // Aquí se procesaría el envío de la orden, por ejemplo, guardar en la base de datos
                                        // En este ejemplo, simplemente mostraremos un mensaje con los detalles de la orden

                                        // Limpiar y destruir la sesión de productos de la orden

                                        echo '<div class="container mt-6">
                                                <div class="notification is-success">
                                                    <h2 class="subtitle">Orden de Compra Enviada</h2>
                                                    <p>Cliente: ' . $clientName . '</p>
                                                    <p>Total: $' . number_format($totalOrden, 2) . '</p>
                                                </div>
                                              </div>';
                                    }
                                }

                                // Mostrar los productos agregados a la orden
                                if (!empty($_SESSION['productosOrden'])) {
                                    foreach ($_SESSION['productosOrden'] as $index => $producto) {
                                        echo '<tr>
                                                <td>' . $producto['nombre'] . '</td>
                                                <td>' . $producto['unidades'] . '</td>
                                                <td>$' . number_format($producto['precio_unitario'], 2) . '</td>
                                                <td>$' . number_format($producto['subtotal'], 2) . '</td>
                                                <td>
                                                    <form method="post" style="display:inline;">
                                                        <input type="hidden" name="index" value="' . $index . '">
                                                        <button class="button is-danger" type="submit" name="remove_from_order">Eliminar</button>
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
                                <label class="label">Buscar Cliente</label>
                                <div class="control">
                                    <input class="input" type="text" name="client_name" placeholder="Escribe el nombre del cliente" required>
                                </div>
                            </div>
                            <button class="button is-primary" type="submit" name="send_order">Enviar Orden</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
