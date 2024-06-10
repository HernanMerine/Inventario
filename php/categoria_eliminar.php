<?php
require_once "main.php";

/* Almacenando datos */
$category_id_del = limpiar_cadena($_GET['category_id_del']);

/*Verificando usuario*/
$conexion = conexion();
$query_categoria = "SELECT categoria_id FROM categoria WHERE categoria_id = '$category_id_del'";
$result_categoria = mysqli_query($conexion, $query_categoria);

if (mysqli_num_rows($result_categoria) == 1) {
    $query_productos = "SELECT categoria_id FROM producto WHERE categoria_id = '$category_id_del' LIMIT 1";
    $result_productos = mysqli_query($conexion, $query_productos);

    if (mysqli_num_rows($result_productos) <= 0) {
        $query_eliminar = "DELETE FROM categoria WHERE categoria_id = '$category_id_del'";
        $result_eliminar = mysqli_query($conexion, $query_eliminar);

        if (mysqli_affected_rows($conexion) == 1) {
            echo '
                <div class="notification is-info is-light">
                    <strong>¡CATEGORIA ELIMINADA!</strong><br>
                    Los datos de la categoría se eliminaron con éxito
                </div>
            ';
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo eliminar la categoría, por favor intente nuevamente
                </div>
            ';
        }
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No podemos eliminar la categoría ya que tiene productos asociados
            </div>
        ';
    }
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La CATEGORIA que intenta eliminar no existe
        </div>
    ';
}

mysqli_close($conexion);
