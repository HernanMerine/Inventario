<?php
require_once "../inc/session_start.php";
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['proveedor_id']);

/*== Verificando proveedor ==*/
$conn = conexion();
$query = "SELECT * FROM proveedor WHERE proveedor_id='$id'";
$check_proveedor = $conn->query($query);

if ($check_proveedor->num_rows <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El proveedor no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = $check_proveedor->fetch_assoc();
}

/*== Almacenando datos del administrador ==*/
$admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
$admin_clave = limpiar_cadena($_POST['administrador_clave']);

/*== Verificando campos obligatorios del administrador ==*/
if ($admin_usuario == "" || $admin_clave == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No ha llenado los campos que corresponden a su USUARIO o CLAVE
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos (admin) ==*/
if (verificar_datos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Su USUARIO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            Su CLAVE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando el administrador en DB ==*/
$query = "SELECT usuario_usuario, usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'";
$check_admin = $conn->query($query);

if ($check_admin->num_rows == 1) {
    $check_admin = $check_admin->fetch_assoc();

    if ($check_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }

} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            USUARIO o CLAVE de administrador incorrectos
        </div>
    ';
    exit();
}

/*== Almacenando datos del proveedor ==*/
$nombre = limpiar_cadena($_POST['proveedor_nombre']);
$email = limpiar_cadena($_POST['proveedor_mail']);
$telefono = limpiar_cadena($_POST['proveedor_telefono']);
$vendedor = limpiar_cadena($_POST['proveedor_vendedor']);
$direccion = limpiar_cadena($_POST['proveedor_direccion']);

/*== Verificando campos obligatorios del proveedor ==*/
if ($nombre == "" || $telefono == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No ha llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos (proveedor) ==*/
if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
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

/*== Actualizar datos ==*/
$query = "UPDATE proveedor SET 
          proveedor_nombre='$nombre',
          proveedor_mail='$email',
          proveedor_telefono='$telefono',
          proveedor_vendedor='$vendedor',
          proveedor_direccion='$direccion'
          WHERE proveedor_id='$id'";

if ($conn->query($query) === TRUE) {
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
            No se pudo actualizar el proveedor, por favor inténtelo nuevamente
        </div>
    ';
}

$conn->close();
