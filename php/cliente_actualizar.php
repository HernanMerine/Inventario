<?php
require_once "../inc/session_start.php";
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['cliente_id']);

/*== Verificando cliente ==*/
$conn = conexion();
$query = "SELECT * FROM cliente WHERE cliente_id='$id'";
$check_cliente = $conn->query($query);

if ($check_cliente->num_rows <= 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El cliente no existe en el sistema
        </div>
    ';
    exit();
} else {
    $datos = $check_cliente->fetch_assoc();
}

/*== Almacenando datos del administrador ==*/
$admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
$admin_clave = limpiar_cadena($_POST['administrador_clave']);

/*== Verificando campos obligatorios del administrador ==*/
if ($admin_usuario == "" || $admin_clave == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No ha llenado los campos que corresponden a su USUARIO o CLAVE
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos (admin) ==*/
if (verificar_datos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Su USUARIO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
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
                <strong>¡Ocurrio un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }

} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            USUARIO o CLAVE de administrador incorrectos
        </div>
    ';
    exit();
}

/*== Almacenando datos del cliente ==*/
$nombre = limpiar_cadena($_POST['cliente_nombre']);
$apellido = limpiar_cadena($_POST['cliente_apellido']);
$email = limpiar_cadena($_POST['cliente_email']);
$direccion = limpiar_cadena($_POST['cliente_direccion']);
$telefono = limpiar_cadena($_POST['cliente_telefono']);

/*== Verificando campos obligatorios del cliente ==*/
if ($nombre == "" || $apellido == "" || $direccion == "" || $telefono == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/*== Verificando integridad de los datos (cliente) ==*/
if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El APELLIDO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9#\-\.\,\ ]{4,100}", $direccion)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La DIRECCIÓN no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[0-9()+]{8,20}", $telefono)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El TELÉFONO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/*== Verificando email ==*/
if ($email != "" && $email != $datos['cliente_email']) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT cliente_email FROM cliente WHERE cliente_email='$email'";
        $check_email = $conn->query($query);
        if ($check_email->num_rows > 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El correo electrónico ingresado ya se encuentra registrado, por favor elija otro
                </div>
            ';
            exit();
        }
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Ha ingresado un correo electrónico no valido
            </div>
        ';
        exit();
    }
}

/*== Actualizar datos ==*/
$query = "UPDATE cliente SET 
          cliente_nombre='$nombre',
          cliente_apellido='$apellido',
          cliente_email='$email',
          cliente_direccion='$direccion',
          cliente_telefono='$telefono'
          WHERE cliente_id='$id'";

if ($conn->query($query) === TRUE) {
    echo '
        <div class="notification is-info is-light">
            <strong>¡CLIENTE ACTUALIZADO!</strong><br>
            El cliente se actualizó con éxito
        </div>
    ';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo actualizar el cliente, por favor intente nuevamente
        </div>
    ';
}

$conn->close();
