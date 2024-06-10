<?php
require_once "../inc/session_start.php";
require_once "main.php";

/*== Almacenando datos ==*/
$nombre = limpiar_cadena($_POST['producto_nombre']);
$costo = limpiar_cadena($_POST['producto_costo']);
$porcentaje = limpiar_cadena($_POST['producto_porcentaje']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);
$proveedor = limpiar_cadena($_POST['producto_proveedor']);

/*== Verificando campos obligatorios ==*/
if ($proveedor == "" || $nombre == "" || $costo == "" || $stock == "" || $categoria == "") {
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
            El PRECIO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $porcentaje)) {
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



/*== Verificando proveedor ==*/
$conexion = conexion();
$query_proveedor = "SELECT proveedor_id FROM proveedor WHERE proveedor_id='$proveedor'";
$result_proveedor = mysqli_query($conexion, $query_proveedor);
if (mysqli_num_rows($result_proveedor) <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La categoría seleccionada no existe
        </div>
    ';
    exit();
}

/*== Verificando nombre ==*/
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

/*== Verificando categoria ==*/
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

/* Directorios de imagenes */
$img_dir = '../img/producto/';

/*== Comprobando si se ha seleccionado una imagen ==*/
if ($_FILES['producto_foto']['name'] != "" && $_FILES['producto_foto']['size'] > 0) {

    /* Creando directorio de imagenes */
    if (!file_exists($img_dir)) {
        if (!mkdir($img_dir, 0777)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    Error al crear el directorio de imágenes
                </div>
            ';
            exit();
        }
    }

    /* Comprobando formato de las imágenes */
    if (mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La imagen que ha seleccionado es de un formato que no está permitido
            </div>
        ';
        exit();
    }

    /* Comprobando que la imagen no supere el peso permitido */
    if (($_FILES['producto_foto']['size'] / 1024) > 3072) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La imagen que ha seleccionado supera el límite de peso permitido
            </div>
        ';
        exit();
    }

    /* extensión de las imágenes */
    switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
        case 'image/jpeg':
            $img_ext = ".jpg";
            break;
        case 'image/png':
            $img_ext = ".png";
            break;
    }

    /* Cambiando permisos al directorio */
    chmod($img_dir, 0777);

    /* Nombre de la imagen */
    $img_nombre = renombrar_fotos($nombre);

    /* Nombre final de la imagen */
    $foto = $img_nombre . $img_ext;

    /* Moviendo imagen al directorio */
    if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir . $foto)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
            </div>
        ';
        exit();
    }
} else {
    $foto = "";
}

/*== Guardando datos ==*/
$conexion = conexion();
$precio_calculado = $costo + ($costo * $porcentaje / 100);
$query_guardar_producto = "INSERT INTO producto (producto_nombre, producto_costo, producto_precio, porcentaje, producto_stock, producto_foto, categoria_id, usuario_id, proveedor_id) VALUES ('$nombre', '$costo', '$precio_calculado', '$porcentaje', '$stock', '$foto', '$categoria', '$_SESSION[id]', '$proveedor')";
if (mysqli_query($conexion, $query_guardar_producto)) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PRODUCTO REGISTRADO!</strong><br>
            El producto se registró con éxito
        </div>
    ';
} else {

    if (is_file($img_dir . $foto)) {
        chmod($img_dir . $foto, 0777);
        unlink($img_dir . $foto);
    }

    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo registrar el producto, por favor intente nuevamente
        </div>
    ';
}
mysqli_close($conexion);