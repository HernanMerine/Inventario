<?php

    /*== Almacenando datos ==*/
    $client_id_del = limpiar_cadena($_GET['client_id_del']);

    // Obteniendo la conexión a la base de datos
    $conexion = conexion();

    if ($conexion->connect_error) {
        die("Connection failed: " . $conexion->connect_error);
    }

    /*== Verificando cliente ==*/
    $check_cliente = $conexion->query("SELECT cliente_id FROM cliente WHERE cliente_id='$client_id_del'");

    if ($check_cliente === false) {
        die("Error en la consulta SQL: " . $conexion->error);
    }

    if ($check_cliente->num_rows == 1) {
        // Eliminando el cliente
        $eliminar_cliente = $conexion->query("DELETE FROM cliente WHERE cliente_id='$client_id_del'");

        if ($eliminar_cliente) {
            echo '
                <div class="notification is-info is-light">
                    <strong>¡CLIENTE ELIMINADO!</strong><br>
                    Los datos del cliente se eliminaron con éxito
                </div>
            ';
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el cliente, por favor inténtelo nuevamente
                </div>
            ';
        }
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El CLIENTE que intenta eliminar no existe
            </div>
        ';
    }

    $check_cliente->close();
    $conexion->close();
    
