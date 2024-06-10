<?php
    /*== Almacenando datos ==*/
    $usuario = limpiar_cadena($_POST['login_usuario']);
    $clave = limpiar_cadena($_POST['login_clave']);

    /*== Verificando campos obligatorios ==*/
    if ($usuario == "" || $clave == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    /*== Verificando integridad de los datos ==*/
    if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La CLAVE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    /*== Verificando usuario y contraseña ==*/
    $conexion = conexion();

    $query = "SELECT * FROM usuario WHERE usuario_usuario = '$usuario'";
    $result = $conexion->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row['usuario_usuario'] == $usuario && password_verify($clave, $row['usuario_clave'])) {
            session_start();
            $_SESSION['id'] = $row['usuario_id'];
            $_SESSION['nombre'] = $row['usuario_nombre'];
            $_SESSION['apellido'] = $row['usuario_apellido'];
            $_SESSION['usuario'] = $row['usuario_usuario'];

            if (headers_sent()) {
                echo "<script> window.location.href='index.php?vista=home'; </script>";
            } else {
                header("Location: index.php?vista=home");
            }
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    Usuario o contraseña incorrectos
                </div>
            ';
        }
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                Usuario o contraseña incorrectos
            </div>
        ';
    }

    $conexion->close();
?>
