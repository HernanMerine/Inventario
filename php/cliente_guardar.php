<?php
require_once "main.php";

/* Almacenando datos */
$nombre = limpiar_cadena($_POST['cliente_nombre']);
$apellido = limpiar_cadena($_POST['cliente_apellido']);
$email = limpiar_cadena($_POST['cliente_email']);
$direccion = limpiar_cadena($_POST['cliente_direccion']);
$telefono = limpiar_cadena($_POST['cliente_telefono']);

/* Verificando campos obligatorios */
if ($nombre == "" || $apellido == "" || $direccion == "" || $telefono == "") {
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

/* Verificando email */
if ($email != "") {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check_email = conexion();
        $query_email = "SELECT cliente_email FROM cliente WHERE cliente_email='$email'";
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

/* Verificando teléfono */
if (verificar_datos("[0-9]{6,15}", $telefono)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El TELÉFONO no coincide con el formato solicitado
        </div>
    ';
    exit();
}

/* Guardando datos */
$guardar_cliente = conexion();
$query_insert = "INSERT INTO cliente (cliente_nombre, cliente_apellido, cliente_email, cliente_direccion, cliente_telefono) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($guardar_cliente, $query_insert);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apellido, $email, $direccion, $telefono);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) == 1) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡CLIENTE REGISTRADO!</strong><br>
                El cliente se registró con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el cliente, por favor intente nuevamente
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

mysqli_close($guardar_cliente);
