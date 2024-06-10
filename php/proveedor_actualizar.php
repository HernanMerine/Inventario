<?php
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['proveedor_id']);

/*== Verificando proveedor ==*/
$conexion = conexion();
$query_proveedor = "SELECT * FROM proveedor WHERE proveedor_id='$id'";
$result_proveedor = mysqli_query($conexion, $query_proveedor);

if (mysqli_num_rows($result_proveedor) <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El proveedor no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = mysqli_fetch_assoc($result_proveedor);
}
mysqli_free_result($result_proveedor);

/*== Almacenando datos ==*/
$nombre = limpiar_cadena($_POST['nombre']);
$contacto = limpiar_cadena($_POST['contacto']);

/*== Verificando campos obligatorios ==*/
if ($nombre == "" || $contacto == "") {
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

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}", $contacto)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El CONTACTO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando nombre ==*/
if ($nombre != $datos['nombre']) {
    $query_nombre = "SELECT nombre FROM proveedor WHERE nombre='$nombre'";
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

/*== Actualizando datos ==*/
$query_update = "UPDATE proveedor SET nombre='$nombre', contacto='$contacto' WHERE proveedor_id='$id'";
if (mysqli_query($conexion, $query_update)) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PROVEEDOR ACTUALIZADO!</strong><br>
            El proveedor se actualizó con éxito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo actualizar el proveedor, por favor intente nuevamente
        </div>
    ';
}

mysqli_close($conexion);
