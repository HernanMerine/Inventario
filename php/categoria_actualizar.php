<?php
require_once "main.php";

/* Almacenando id */
$id = limpiar_cadena($_POST['categoria_id']);

/* Verificando categoria */
$conexion = conexion();
$query_categoria = "SELECT * FROM categoria WHERE categoria_id = '$id'";
$result_categoria = mysqli_query($conexion, $query_categoria);

if (mysqli_num_rows($result_categoria) <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La categoría no existe en el sistema
        </div>
    ';
    mysqli_close($conexion);
    exit();
} else {
    $datos = mysqli_fetch_assoc($result_categoria);
}

/* Almacenando datos */
$nombre = limpiar_cadena($_POST['categoria_nombre']);
$ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

/* Verificando campos obligatorios */
if ($nombre == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    mysqli_close($conexion);
    exit();
}

/* Verificando integridad de los datos */
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    mysqli_close($conexion);
    exit();
}

if ($ubicacion != "") {
    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}", $ubicacion)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La UBICACION no coincide con el formato solicitado
            </div>
        ';
        mysqli_close($conexion);
        exit();
    }
}

/* Verificando nombre */
if ($nombre != $datos['categoria_nombre']) {
    $query_nombre = "SELECT categoria_nombre FROM categoria WHERE categoria_nombre = '$nombre'";
    $result_nombre = mysqli_query($conexion, $query_nombre);

    if (mysqli_num_rows($result_nombre) > 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        mysqli_close($conexion);
        exit();
    }
}

/* Actualizar datos */
$query_update = "UPDATE categoria SET categoria_nombre = '$nombre', categoria_ubicacion = '$ubicacion' WHERE categoria_id = '$id'";

if (mysqli_query($conexion, $query_update)) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡CATEGORIA ACTUALIZADA!</strong><br>
            La categoría se actualizo con exito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo actualizar la categoría, por favor intente nuevamente
        </div>
    ';
}

mysqli_close($conexion);
