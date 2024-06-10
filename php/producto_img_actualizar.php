<?php
    require_once "main.php";

    /*== Almacenando datos ==*/
    $product_id=limpiar_cadena($_POST['img_up_id']);

    /*== Verificando producto ==*/
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$product_id'");

    if($check_producto->num_rows==1){
        $datos=$check_producto->fetch_assoc();
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La imagen del PRODUCTO que intenta actualizar no existe
            </div>
        ';
        exit();
    }
    $check_producto->close();


    /*== Comprobando si se ha seleccionado una imagen ==*/
    if($_FILES['producto_foto']['name']=="" || $_FILES['producto_foto']['size']==0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No ha seleccionado ninguna imagen o foto
            </div>
        ';
        exit();
    }


    /* Directorios de imagenes */
    $img_dir='../img/producto/';

    /* Creando directorio de imagenes */
    if(!file_exists($img_dir)){
        if(!mkdir($img_dir,0777)){
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    Error al crear el directorio de imágenes
                </div>
            ';
            exit();
        }
    }

    /* Cambiando permisos al directorio */
    chmod($img_dir, 0777);

    /* Comprobando formato de las imágenes */
    $file_type = mime_content_type($_FILES['producto_foto']['tmp_name']);
    if($file_type != "image/jpeg" && $file_type != "image/png"){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La imagen que ha seleccionado es de un formato que no está permitido
            </div>
        ';
        exit();
    }

    /* Comprobando que la imagen no supere el peso permitido */
    if(($_FILES['producto_foto']['size']/1024) > 3072){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La imagen que ha seleccionado supera el límite de peso permitido
            </div>
        ';
        exit();
    }

    /* Extension de las imágenes */
    $img_ext = $file_type == "image/jpeg" ? ".jpg" : ".png";

    /* Nombre de la imagen */
    $img_nombre = renombrar_fotos($datos['producto_nombre']);

    /* Nombre final de la imagen */
    $foto = $img_nombre.$img_ext;

    /* Moviendo imagen al directorio */
    if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir.$foto)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
            </div>
        ';
        exit();
    }

    /* Eliminando la imagen anterior */
    if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto'] != $foto){
        chmod($img_dir.$datos['producto_foto'], 0777);
        unlink($img_dir.$datos['producto_foto']);
    }

    /*== Actualizando datos ==*/
    $conexion = conexion();
    $query_actualizar_producto = "UPDATE producto SET producto_foto='$foto' WHERE producto_id='$product_id'";
    if(mysqli_query($conexion, $query_actualizar_producto)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡IMAGEN O FOTO ACTUALIZADA!</strong><br>
                La imagen del producto ha sido actualizada exitosamente, pulse Aceptar para recargar los cambios.
                <p class="has-text-centered pt-5 pb-5">
                    <a href="index.php?vista=product_img&product_id_up='.$product_id.'" class="button is-link is-rounded">Aceptar</a>
                </p>
            </div>
        ';
    }else{
        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto, 0777);
            unlink($img_dir.$foto);
        }

        echo '
            <div class="notification is-warning is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
              No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_producto=null;