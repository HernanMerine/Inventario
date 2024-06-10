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
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);
$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);

/*== Verificando campos obligatorios ==*/
if ($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos ==*/
if (verificar_datos("[a-zA-Z0-9- ]{1,70}", $codigo)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El CÓDIGO de BARRAS no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $precio)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PRECIO no coincide con el formato solicitado
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

/*== Verificando codigo ==*/
if ($codigo != $datos['producto_codigo']) {
    $query_codigo = "SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'";
    $result_codigo = mysqli_query($conexion, $query_codigo);

    if (mysqli_num_rows($result_codigo) > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El CÓDIGO de BARRAS ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    mysqli_free_result($result_codigo);
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
$query_update = "UPDATE producto SET producto_codigo='$codigo', producto_nombre='$nombre', producto_precio='$precio', producto_stock='$stock', categoria_id='$categoria' WHERE producto_id='$id'";
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