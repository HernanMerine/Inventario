<?php
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['producto_id']);

/*== Verificando producto ==*/
$conexion = conexion();
$query_producto = "SELECT * FROM producto WHERE producto_id='$id'";
$result_producto = mysqli_query($conexion, $query_producto);

if (mysqli_num_rows($result_producto) <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El producto no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = mysqli_fetch_assoc($result_producto);
}
mysqli_free_result($result_producto);

/*== Almacenando datos ==*/
$nombre = limpiar_cadena($_POST['producto_nombre']);
$costo = limpiar_cadena($_POST['producto_costo']);
$porcentaje = limpiar_cadena($_POST['producto_porcentaje']);
$stock = limpiar_cadena($_POST['producto_stock']);
$stock_minimo = limpiar_cadena($_POST['producto_stock_minimo']);
$categoria = limpiar_cadena($_POST['producto_categoria']);

/*== Verificando campos obligatorios ==*/
if ($nombre == "" || $costo == "" || $stock == "" || $stock_minimo == "" || $categoria == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos ==*/

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $costo)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El COSTO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $porcentaje)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PORCENTAJE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9]{1,25}", $stock)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El STOCK no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9]{1,25}", $stock_minimo)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El STOCK MÍNIMO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando nombre ==*/
if ($nombre != $datos['producto_nombre']) {
    $query_nombre = "SELECT producto_nombre FROM producto WHERE producto_nombre='$nombre'";
    $result_nombre = mysqli_query($conexion, $query_nombre);

    if (mysqli_num_rows($result_nombre) > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    mysqli_free_result($result_nombre);
}

/*== Verificando categoria ==*/
if ($categoria != $datos['categoria_id']) {
    $query_categoria = "SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'";
    $result_categoria = mysqli_query($conexion, $query_categoria);

    if (mysqli_num_rows($result_categoria) <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La categoría seleccionada no existe
            </div>
        ';
        exit();
    }
    mysqli_free_result($result_categoria);
}

/*== Actualizando datos ==*/
$precio_calculado = $costo + ($costo * $porcentaje / 100);
$query_update = "UPDATE producto SET producto_nombre='$nombre', producto_costo='$costo', producto_precio='$precio_calculado', producto_stock='$stock', producto_stock_minimo='$stock_minimo', categoria_id='$categoria', porcentaje='$porcentaje' WHERE producto_id='$id'";
if (mysqli_query($conexion, $query_update)) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
            El producto se actualizó con éxito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo actualizar el producto, por favor intente nuevamente
        </div>
    ';
}

mysqli_close($conexion);
