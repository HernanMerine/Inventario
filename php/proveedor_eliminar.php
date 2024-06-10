<?php
require_once "main.php";

/*== Almacenando datos ==*/
$proveedor_id_del = limpiar_cadena($_GET['proveedor_id_del']);

/*== Verificando proveedor ==*/
$conexion = conexion();
$query_proveedor = "SELECT * FROM proveedor WHERE proveedor_id='$proveedor_id_del'";
$result_proveedor = mysqli_query($conexion, $query_proveedor);

if (mysqli_num_rows($result_proveedor) == 1) {
    $eliminar_proveedor = "DELETE FROM proveedor WHERE proveedor_id='$proveedor_id_del'";
    if (mysqli_query($conexion, $eliminar_proveedor)) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡PROVEEDOR ELIMINADO!</strong><br>
                Los datos del proveedor se eliminaron con éxito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo eliminar el proveedor, por favor inténtelo nuevamente
            </div>
        ';
    }
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PROVEEDOR que intenta eliminar no existe
        </div>
    ';
}

mysqli_close($conexion);
