<?php

/*== Almacenando datos ==*/
$proveedor_id_del = limpiar_cadena($_GET['proveedor_id_del']);

// Obteniendo la conexión a la base de datos
$conexion = conexion();

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

/*== Verificando proveedor ==*/
$check_proveedor = $conexion->query("SELECT proveedor_id FROM proveedor WHERE proveedor_id='$proveedor_id_del'");

if($check_proveedor->num_rows == 1){

    $check_productos = $conexion->query("SELECT producto_id FROM producto WHERE proveedor_id='$proveedor_id_del' LIMIT 1");

    if($check_productos->num_rows <= 0){
        
        // Eliminando el proveedor
        $eliminar_proveedor = $conexion->query("DELETE FROM proveedor WHERE proveedor_id='$proveedor_id_del'");

        if($eliminar_proveedor){
            echo '
                <div class="notification is-info is-light">
                    <strong>¡PROVEEDOR ELIMINADO!</strong><br>
                    Los datos del proveedor se eliminaron con éxito
                </div>
            ';
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el proveedor, por favor inténtelo nuevamente
                </div>
            ';
        }
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No podemos eliminar el proveedor ya que tiene productos registrados
            </div>
        ';
    }
    $check_productos->close();
}else{
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El PROVEEDOR que intenta eliminar no existe
        </div>
    ';
}
$check_proveedor->close();
$conexion->close();
