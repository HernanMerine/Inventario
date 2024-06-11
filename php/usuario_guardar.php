<?php
require_once "main.php";

/*Almacenando datos */
$nombre = limpiar_cadena($_POST['usuario_nombre']);
$apellido = limpiar_cadena($_POST['usuario_apellido']);
$usuario = limpiar_cadena($_POST['usuario_usuario']);
$email = limpiar_cadena($_POST['usuario_email']);
$clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
$clave_2 = limpiar_cadena($_POST['usuario_clave_2']);
$rol = limpiar_cadena($_POST['rol']);

/*Verificando campos obligatorios */
if ($nombre == "" || $apellido == "" || $usuario == "" || $clave_1 == "" || $clave_2 == "" || $rol == "") {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado todos los campos que son obligatorios
        </div>
    ';
    exit();
}

/* Verificando integridad de los datos */
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

if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_2)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las CLAVES no coinciden con el formato solicitado
        </div>
    ';
    exit();
}

/*Verificando email */
if ($email != "") {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check_email = conexion();
        $query_email = "SELECT usuario_email FROM usuario WHERE usuario_email='$email'";
        $result_email = mysqli_query($check_email, $query_email);
        if (mysqli_num_rows($result_email) > 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El correo electrónico ingresado ya se encuentra registrado, por favor elija otro
                </div>
            ';
            mysqli_close($check_email);
            exit();
        }
        mysqli_close($check_email);
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

/* Verificando usuario */
$check_usuario = conexion();
$query_usuario = "SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'";
$result_usuario = mysqli_query($check_usuario, $query_usuario);
if (mysqli_num_rows($result_usuario) > 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El USUARIO ingresado ya se encuentra registrado, por favor elija otro
        </div>
    ';
    mysqli_close($check_usuario);
    exit();
}
mysqli_close($check_usuario);

/* Verificando claves */
if ($clave_1 != $clave_2) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las CLAVES que ha ingresado no coinciden
        </div>
    ';
    exit();
} else {
    $clave = password_hash($clave_1, PASSWORD_BCRYPT, ["cost" => 10]);
}

/* Guardando datos */
$guardar_usuario = conexion();
$query_insert = "INSERT INTO usuario (usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email, rol) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($guardar_usuario, $query_insert);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $apellido, $usuario, $clave, $email, $rol);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) == 1) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO REGISTRADO!</strong><br>
                El usuario se registró con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el usuario, por favor intente nuevamente
            </div>
        ';
    }

    mysqli_stmt_close($stmt);
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo preparar la consulta
        </div>
    ';
}

mysqli_close($guardar_usuario);
