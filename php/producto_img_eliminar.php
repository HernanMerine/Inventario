<?php
require_once "main.php";

/*== Almacenando datos ==*/
$product_id = limpiar_cadena($_POST['img_del_id']);

/*== Verificando producto ==*/
$conexion = conexion();
$check_producto = $conexion->query("SELECT * FROM producto WHERE producto_id='$product_id'");

if ($check_producto->num_rows == 1) {
    $datos = $check_producto->fetch_assoc();
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La imagen del PRODUCTO que intenta eliminar no existe
        </div>
    ';
    exit();
}
$check_producto->close();

/* Directorios de imágenes */
$img_dir = '../img/producto/';

/* Cambiando permisos al directorio */
chmod($img_dir, 0777);

/* Eliminando la imagen */
if (is_file($img_dir . $datos['producto_foto'])) {
    chmod($img_dir . $datos['producto_foto'], 0777);
    if (!unlink($img_dir . $datos['producto_foto'])) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Error al intentar eliminar la imagen del producto, por favor inténtelo nuevamente
            </div>
        ';
        exit();
    }
}

/*== Actualizando datos ==*/
$actualizar_producto = $conexion->query("UPDATE producto SET producto_foto='' WHERE producto_id='$product_id'");

if ($actualizar_producto) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
            La imagen del producto ha sido eliminada exitosamente, pulse Aceptar para recargar los cambios.

            <p class="has-text-centered pt-5 pb-5">
                <a href="index.php?vista=product_img&product_id_up=' . $product_id . '" class="button is-link is-rounded">Aceptar</a>
            </p">
        </div>
    ';
} else {
    echo '
        <div class="notification is-warning is-light">
            <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
            Ocurrieron algunos inconvenientes, sin embargo la imagen del producto ha sido eliminada, pulse Aceptar para recargar los cambios.

            <p class="has-text-centered pt-5 pb-5">
                <a href="index.php?vista=product_img&product_id_up=' . $product_id . '" class="button is-link is-rounded">Aceptar</a>
            </p">
        </div>
    ';
}

$conexion->close();
$actualizar_producto = null;
