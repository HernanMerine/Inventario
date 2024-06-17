<?php
require_once "./php/main.php";

$conexion = conexion();

$id = (isset($_GET['orden_id_up'])) ? $_GET['orden_id_up'] : 0;
$id = limpiar_cadena($id);
?>
<div class="container is-fluid mb-6">
    <h1 class="title">Órdenes de Compra</h1>
    <h2 class="subtitle">Ver Orden de Compra</h2>
</div>

<div class="container pb-6 pt-6 pl-5 pr-5">
    <?php
    include "./inc/btn_back.php";

    /*== Verificando orden de compra ==*/
    $conn = conexion();
    $query = "SELECT orden_de_compra.*, cliente.cliente_nombre, cliente.cliente_apellido, usuario.usuario_nombre, usuario.usuario_apellido
              FROM orden_de_compra
              JOIN cliente ON orden_de_compra.cliente_id = cliente.cliente_id
              JOIN usuario ON orden_de_compra.vendedor_id = usuario.usuario_id
              WHERE orden_id='$id'";
    $check_orden = $conn->query($query);

    if ($check_orden->num_rows > 0) {
        $datos = $check_orden->fetch_assoc();

        // Consulta para obtener los detalles de la orden
        $detalle_query = "SELECT detalle_orden_de_compra.*, producto.producto_nombre 
                          FROM detalle_orden_de_compra 
                          JOIN producto ON detalle_orden_de_compra.producto_id = producto.producto_id 
                          WHERE orden_id='$id'";
        $detalle_result = $conn->query($detalle_query);
    ?>

    <div class="form-rest mb-6 mt-6"></div>

    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Cliente</label>
                <input class="input" type="text" readonly value="<?php echo $datos['cliente_nombre'] . ' ' . $datos['cliente_apellido']; ?>">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Vendedor</label>
                <input class="input" type="text" readonly value="<?php echo $datos['usuario_nombre'] . ' ' . $datos['usuario_apellido']; ?>">
            </div>
        </div>
    </div>

    <!-- Tabla de detalles de la orden -->
    <div class="columns">
        <div class="column">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($detalle_result->num_rows > 0) {
                        while ($detalle = $detalle_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $detalle['producto_nombre'] . '</td>';
                            echo '<td>' . $detalle['cantidad'] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="2">No hay detalles para esta orden.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Campo de Total -->
    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Total</label>
                <input class="input" type="number" step="0.01" readonly value="<?php echo $datos['total']; ?>">
            </div>
        </div>
    </div>

    <?php 
    } else {
        include "./inc/error_alert.php";
    }
    $check_orden->free();
    $conn->close();
    ?>
</div>
