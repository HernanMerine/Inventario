<?php
    require_once "./php/main.php";

    $conexion = conexion();

    $id = (isset($_GET['cliente_id_up'])) ? $_GET['cliente_id_up'] : 0;
    $id = limpiar_cadena($id);
?>

        <section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
              
        <h1 class="title">Clientes</h1>
        <h2 class="subtitle">Actualizar datos del cliente.</h2> 
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
        include "./inc/btn_back.php";

        /*== Verificando cliente ==*/
        $conn = conexion();
        $query = "SELECT * FROM cliente WHERE cliente_id='$id'";
        $check_cliente = $conn->query($query);

        if ($check_cliente->num_rows > 0) {
            $datos = $check_cliente->fetch_assoc();
    ?>

    <div class="form-rest mb-6 mt-6"></div>

    <form action="./php/cliente_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
        <input type="hidden" name="cliente_id" value="<?php echo $datos['cliente_id']; ?>" required>
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombres</label>
                    <input class="input" type="text" name="cliente_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['cliente_nombre']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Apellidos</label>
                    <input class="input" type="text" name="cliente_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['cliente_apellido']; ?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Email</label>
                    <input class="input" type="email" name="cliente_email" maxlength="70" value="<?php echo $datos['cliente_email']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Dirección</label>
                    <input class="input" type="text" name="cliente_direccion" pattern="[a-zA-Z0-9#\-\.\,\ ]{4,100}" maxlength="100" required value="<?php echo $datos['cliente_direccion']; ?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Teléfono</label>
                    <input class="input" type="text" name="cliente_telefono" pattern="[0-9()+]{8,20}" maxlength="20" required value="<?php echo $datos['cliente_telefono']; ?>">
                </div>
            </div>
        </div>

        <p class="has-text-centered">
            Para poder actualizar los datos de este cliente por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
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
        $check_cliente->free();
        $conn->close();
    ?>
</div>
