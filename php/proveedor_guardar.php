<?php
require_once "../inc/session_start.php";
require_once "main.php";

/*== Almacenando datos ==*/
$nombre = limpiar_cadena($_POST['proveedor_nombre']);
$email = limpiar_cadena($_POST['proveedor_mail']);
$telefono = limpiar_cadena($_POST['proveedor_telefono']);
$vendedor = limpiar_cadena($_POST['proveedor_vendedor']);
$direccion = limpiar_cadena($_POST['proveedor_direccion']);

/*== Verificando campos obligatorios ==*/
if ($nombre == "" || $telefono == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos ==*/
if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El CORREO ingresado no es válido
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9]{7,20}", $telefono)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El TELÉFONO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando nombre único ==*/
$conexion = conexion();
$query_nombre = "SELECT proveedor_nombre FROM proveedor WHERE proveedor_nombre='$nombre'";
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

/*== Guardando datos ==*/
$query_guardar_proveedor = "INSERT INTO proveedor (proveedor_nombre, proveedor_mail, proveedor_telefono, proveedor_vendedor, proveedor_direccion) VALUES ('$nombre', '$email', '$telefono', '$vendedor', '$direccion')";
if (mysqli_query($conexion, $query_guardar_proveedor)) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡PROVEEDOR REGISTRADO!</strong><br>
            El proveedor se registró con éxito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo registrar el proveedor, por favor intente nuevamente
        </div>
    ';
}
mysqli_close($conexion);
