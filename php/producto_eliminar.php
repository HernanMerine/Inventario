producto_eliminar

<?php
require_once "main.php";

/*== Almacenando datos ==*/
$product_id_del = limpiar_cadena($_GET['product_id_del']);

/*== Verificando producto ==*/
$conexion = conexion();
$query_producto = "SELECT * FROM producto WHERE producto_id='$product_id_del'";
$result_producto = mysqli_query($conexion, $query_producto);

if (mysqli_num_rows($result_producto) == 1) {
    $datos = mysqli_fetch_assoc($result_producto);

    $eliminar_producto = "DELETE FROM producto WHERE producto_id='$product_id_del'";
    if (mysqli_query($conexion, $eliminar_producto)) {
        if (is_file("./img/producto/" . $datos['producto_foto'])) {
            chmod("./img/producto/" . $datos['producto_foto'], 0777);
            unlink("./img/producto/" . $datos['producto_foto']);
        }

        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO ELIMINADO!</strong><br>
                Los datos del producto se eliminaron con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo eliminar el producto, por favor inténtelo nuevamente
            </div>
        ';
    }
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PRODUCTO que intenta eliminar no existe
        </div>
    ';
}

mysqli_close($conexion);
