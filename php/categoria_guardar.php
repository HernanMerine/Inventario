<?php
require_once "main.php";

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
        exit();
    }
}

/* Verificando nombre */
$check_nombre = conexion();
$query_nombre = "SELECT categoria_nombre FROM categoria WHERE categoria_nombre = ?";
$stmt_nombre = mysqli_prepare($check_nombre, $query_nombre);
mysqli_stmt_bind_param($stmt_nombre, "s", $nombre);
mysqli_stmt_execute($stmt_nombre);
mysqli_stmt_store_result($stmt_nombre);

if (mysqli_stmt_num_rows($stmt_nombre) > 0) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
        </div>
    ';
    mysqli_stmt_close($stmt_nombre);
    mysqli_close($check_nombre);
    exit();
}

mysqli_stmt_close($stmt_nombre);
mysqli_close($check_nombre);

/* Guardando datos*/
$guardar_categoria = conexion();
$query_insert = "INSERT INTO categoria (categoria_nombre, categoria_ubicacion) VALUES (?, ?)";
$stmt_insert = mysqli_prepare($guardar_categoria, $query_insert);

if ($stmt_insert) {
    mysqli_stmt_bind_param($stmt_insert, "ss", $nombre, $ubicacion);
    mysqli_stmt_execute($stmt_insert);

    if (mysqli_stmt_affected_rows($stmt_insert) == 1) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡CATEGORIA REGISTRADA!</strong><br>
                La categoría se registró con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar la categoría, por favor intente nuevamente
            </div>
        ';
    }

    mysqli_stmt_close($stmt_insert);
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo preparar la consulta
        </div>
    ';
}

mysqli_close($guardar_categoria);
