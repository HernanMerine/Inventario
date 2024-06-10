<?php

    /*== Almacenando datos ==*/
    $user_id_del = limpiar_cadena($_GET['user_id_del']);

    // Obteniendo la conexión a la base de datos
    $conexion = conexion();

    /*== Verificando usuario ==*/
    $check_usuario = $conexion->query("SELECT usuario_id FROM usuario WHERE usuario_id='$user_id_del'");
    
    if($check_usuario->num_rows == 1){

        $check_productos = $conexion->query("SELECT usuario_id FROM producto WHERE usuario_id='$user_id_del' LIMIT 1");

        if($check_productos->num_rows <= 0){
            
            // Eliminando el usuario
            $eliminar_usuario = $conexion->query("DELETE FROM usuario WHERE usuario_id='$user_id_del'");

            if($eliminar_usuario){
                echo '
                    <div class="notification is-info is-light">
                        <strong>¡USUARIO ELIMINADO!</strong><br>
                        Los datos del usuario se eliminaron con éxito
                    </div>
                ';
            }else{
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrió un error inesperado!</strong><br>
                        No se pudo eliminar el usuario, por favor inténtelo nuevamente
                    </div>
                ';
            }
        }else{
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No podemos eliminar el usuario ya que tiene productos registrados por él
                </div>
            ';
        }
        $check_productos->close();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El USUARIO que intenta eliminar no existe
            </div>
        ';
    }
    $check_usuario->close();
    $conexion->close();
?>