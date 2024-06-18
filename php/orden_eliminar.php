<?php

/*== Almacenando datos ==*/
$order_id_del = limpiar_cadena($_GET['order_id_del']);

// Obteniendo la conexión a la base de datos
$conexion = conexion();

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

/*== Verificando orden de compra ==*/
$check_orden = $conexion->query("SELECT orden_id FROM orden_de_compra WHERE orden_id='$order_id_del'");

if ($check_orden->num_rows == 1) {
    // Eliminando la orden de compra
    $eliminar_orden = $conexion->query("DELETE FROM orden_de_compra WHERE orden_id='$order_id_del'");

    if ($eliminar_orden) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡ORDEN DE COMPRA ELIMINADA!</strong><br>
                Los datos de la orden de compra se eliminaron con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo eliminar la orden de compra, por favor inténtelo nuevamente
            </div>
        ';
    }
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La orden de compra que intenta eliminar no existe
        </div>
    ';
}
$check_orden->close();
$conexion->close();
