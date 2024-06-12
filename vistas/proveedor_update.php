<div class="container is-fluid mb-6">
    <h1 class="title">Proveedores</h1>
    <h2 class="subtitle">Actualizar proveedor</h2>
</div>

<div class="container pb-6 pt-6 pl-5 pr-5">
    <?php
        include "./inc/btn_back.php";

        require_once "./php/main.php";

        $id = (isset($_GET['proveedor_id_up'])) ? $_GET['proveedor_id_up'] : 0;
        $id = limpiar_cadena($id);

        /*== Verificando proveedor ==*/
        $conexion = conexion();

        $check_proveedor = $conexion->query("SELECT * FROM proveedor WHERE proveedor_id='$id'");

        if ($check_proveedor->num_rows > 0) {
            $datos = $check_proveedor->fetch_assoc();
    ?>

    <div class="form-rest mb-6 mt-6"></div>
    
    <h2 class="title has-text-centered"><?php echo $datos['nombre']; ?></h2>

    <form action="./php/proveedor_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">

        <input type="hidden" name="proveedor_id" value="<?php echo $datos['proveedor_id']; ?>" required>

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre</label>
                    <input class="input" type="text" name="nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required value="<?php echo $datos['nombre']; ?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Contacto</label>
                    <input class="input" type="text" name="contacto" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required value="<?php echo $datos['contacto']; ?>">
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
        $check_proveedor = null;
    ?>
</div>
