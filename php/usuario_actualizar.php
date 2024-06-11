<?php
    require_once "../inc/session_start.php";
    require_once "main.php";

    /*== Almacenando id ==*/
    $id = limpiar_cadena($_POST['usuario_id']);

    /*== Verificando usuario ==*/
    $conn = conexion();
    $query = "SELECT * FROM usuario WHERE usuario_id='$id'";
    $check_usuario = $conn->query($query);

    if ($check_usuario->num_rows <= 0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario no existe en el sistema
            </div>
        ';
        exit();
    } else {
        $datos = $check_usuario->fetch_assoc();
    }

    /*== Almacenando datos del administrador ==*/
    $admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave = limpiar_cadena($_POST['administrador_clave']);

    /*== Verificando campos obligatorios del administrador ==*/
    if ($admin_usuario == "" || $admin_clave == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado los campos que corresponden a su USUARIO o CLAVE
            </div>
        ';
        exit();
    }

    /*== Verificando integridad de los datos (admin) ==*/
    if (verificar_datos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Su CLAVE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    /*== Verificando el administrador en DB ==*/
    $query = "SELECT usuario_usuario, usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'";
    $check_admin = $conn->query($query);

    if ($check_admin->num_rows == 1) {
        $check_admin = $check_admin->fetch_assoc();

        if ($check_admin['usuario_usuario'] != $admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    USUARIO o CLAVE de administrador incorrectos
                </div>
            ';
            exit();
        }

    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }

    /*== Almacenando datos del usuario ==*/
    $nombre = limpiar_cadena($_POST['usuario_nombre']);
    $apellido = limpiar_cadena($_POST['usuario_apellido']);
    $usuario = limpiar_cadena($_POST['usuario_usuario']);
    $email = limpiar_cadena($_POST['usuario_email']);
    $clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2 = limpiar_cadena($_POST['usuario_clave_2']);
    $rol = limpiar_cadena($_POST['rol']);

    /*== Verificando campos obligatorios del usuario ==*/
    if ($nombre == "" || $apellido == "" || $usuario == "" || $rol == "") {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }

    /*== Verificando integridad de los datos (usuario) ==*/
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

    /*== Verificando email ==*/
    if ($email != "" && $email != $datos['usuario_email']) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT usuario_email FROM usuario WHERE usuario_email='$email'";
            $check_email = $conn->query($query);
            if ($check_email->num_rows > 0) {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El correo electrónico ingresado ya se encuentra registrado, por favor elija otro
                    </div>
                ';
                exit();
            }
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

    /*== Verificando usuario ==*/
    if ($usuario != $datos['usuario_usuario']) {
        $query = "SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'";
        $check_usuario = $conn->query($query);
        if ($check_usuario->num_rows > 0) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El USUARIO ingresado ya se encuentra registrado, por favor elija otro
                </div>
            ';
            exit();
        }
    }

    /*== Verificando claves ==*/
    if ($clave_1 != "" || $clave_2 != "") {
        if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_2)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Las CLAVES no coinciden con el formato solicitado
                </div>
            ';
            exit();
        } else {
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
        }
    } else {
        $clave = $datos['usuario_clave'];
    }

    /*== Actualizar datos ==*/
    $query = "UPDATE usuario SET 
              usuario_nombre='$nombre',
              usuario_apellido='$apellido',
              usuario_usuario='$usuario',
              usuario_clave='$clave',
              usuario_email='$email',
              rol='$rol'
              WHERE usuario_id='$id'";

    if ($conn->query($query) === TRUE) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO ACTUALIZADO!</strong><br>
                El usuario se actualizo con exito
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el usuario, por favor intente nuevamente
            </div>
        ';
    }

    $conn->close();

