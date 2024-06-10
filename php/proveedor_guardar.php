<?php
require_once "../inc/session_start.php";
require_once "main.php";

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
$conexion = conexion();
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

/*== Guardando datos ==*/
$query_guardar_proveedor = "INSERT INTO proveedor (nombre, contacto) VALUES ('$nombre', '$contacto')";
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
