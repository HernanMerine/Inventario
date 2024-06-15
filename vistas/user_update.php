<?php
require_once "./php/main.php";

$conexion = conexion();

$id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0;
$id = limpiar_cadena($id);
?>
<div class="container is-fluid mb-6">
    <?php if ($id == $_SESSION['id']) { ?>
        <h1 class="title">Mi cuenta</h1>
        <h2 class="subtitle">Actualizar datos de cuenta</h2>
    <?php } else { ?>
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Actualizar usuario</h2>
    <?php } ?>
</div>

<div class="container pb-6 pt-6 pl-5 pr-5">
    <?php
    include "./inc/btn_back.php";

    /*== Verificando usuario ==*/
    $query = "SELECT * FROM usuario WHERE usuario_id='$id'";
    $check_usuario = $conexion->query($query);

    if ($check_usuario->num_rows > 0) {
        $datos = $check_usuario->fetch_assoc();

        $rol_usuario = $datos['rol'];
        $es_administrador = ($rol_usuario == 1); 
		
    ?>

		<div class="form-rest mb-6 mt-6"></div>
		
        <form action="./php/usuario_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
            <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>" required>
            
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombres</label>
                        <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['usuario_nombre']; ?>">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Apellidos</label>
                        <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['usuario_apellido']; ?>">
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Usuario</label>
                        <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required value="<?php echo $datos['usuario_usuario']; ?>">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Email</label>
                        <input class="input" type="email" name="usuario_email" maxlength="70" value="<?php echo $datos['usuario_email']; ?>">
                    </div>
                </div>
            </div>
            <?php if ($es_administrador) { ?>
            <div class="columns">
                <div class="column">
                    <label>Rol</label><br>
                    <div class="select is-rounded">
                        <select name="rol">
                            <option value="" selected>Seleccione una opción</option>
                            <?php
                            $roles = $conexion->query("SELECT * FROM rol");
                            if ($roles->num_rows > 0) {
                                while ($row = $roles->fetch_assoc()) {
                                    $selected = ($row['rol_id'] == $rol_usuario) ? 'selected' : '';
                                    echo '<option value="' . $row['rol_id'] . '" ' . $selected . '>' . $row['nombre'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <input type="hidden" name="rol" value="<?php echo $rol_usuario; ?>">
            <?php } ?>
            <br>
            <p class="has-text-centered">
                SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
            </p>
            <br>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Clave</label>
                        <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Repetir clave</label>
                        <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                    </div>
                </div>
            </div>
            <br>

            <p class="has-text-centered">
                Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
            </p>
            <br>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Usuario</label>
                        <input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Clave</label>
                        <input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                    </div>
                </div>
            </div>
            <p class="has-text-centered">
                <button type="submit" class="button is-success is-rounded">Actualizar</button>
            </p>
        </form>
    <?php
    } else {
        include "./inc/error_alert.php";
    }
    $check_usuario->free();
    $conexion->close();
    ?>
</div>
