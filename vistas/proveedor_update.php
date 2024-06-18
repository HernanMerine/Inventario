<?php
require_once "./php/main.php";

$conexion = conexion();

$id = (isset($_GET['proveedor_id_up'])) ? $_GET['proveedor_id_up'] : 0;
$id = limpiar_cadena($id);
?>
<section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
                <h1 class="title">Proveedores</h1>
        <h2 class="subtitle">Actualizar proveedor</h2>
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
    include "./inc/btn_back.php";

    /*== Verificando proveedor ==*/
    $conn = conexion();
    $query = "SELECT * FROM proveedor WHERE proveedor_id='$id'";
    $check_proveedor = $conn->query($query);

    if ($check_proveedor->num_rows > 0) {
        $datos = $check_proveedor->fetch_assoc();
    ?>

    <div class="form-rest mb-6 mt-6"></div>

    <form action="./php/proveedor_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
        <input type="hidden" name="proveedor_id" value="<?php echo $datos['proveedor_id']; ?>" required>
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre</label>
                    <input class="input" type="text" name="proveedor_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['proveedor_nombre']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Correo Electrónico</label>
                    <input class="input" type="email" name="proveedor_mail" maxlength="70" value="<?php echo $datos['proveedor_mail']; ?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Teléfono</label>
                    <input class="input" type="text" name="proveedor_telefono" pattern="[0-9]{7,20}" maxlength="20" required value="<?php echo $datos['proveedor_telefono']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Vendedor</label>
                    <input class="input" type="text" name="proveedor_vendedor" maxlength="50" value="<?php echo $datos['proveedor_vendedor']; ?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Dirección</label>
                    <textarea class="textarea" name="proveedor_direccion" rows="3"><?php echo $datos['proveedor_direccion']; ?></textarea>
                </div>
            </div>
        </div>

        <p class="has-text-centered">
            Para poder actualizar los datos de este proveedor, ingrese su USUARIO y CLAVE con la que ha iniciado sesión
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
    $check_proveedor->free();
    $conn->close();
    ?>
</div>
