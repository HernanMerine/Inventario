<div class="container is-fluid mb-6">
    <h1 class="title">Órdenes de Compra</h1>
    <h2 class="subtitle">Buscar Orden de Compra</h2>
</div>

<div class="container pb-6 pt-6 pl-5 pr-5">
    <?php
    require_once "./php/main.php";

    if (isset($_POST['modulo_buscador'])) {
        require_once "./php/buscador.php";
    }

    if (!isset($_SESSION['busqueda_orden_de_compra']) || empty($_SESSION['busqueda_orden_de_compra'])) {
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_buscador" value="orden_de_compra">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="Buscar por vendedor, cliente o fecha" maxlength="50">
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit">Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php } else { ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_buscador" value="orden_de_compra">
                <input type="hidden" name="eliminar_buscador" value="orden_de_compra">
                <p>Estás buscando <strong>“<?php echo $_SESSION['busqueda_orden_de_compra']; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar búsqueda</button>
            </form>
        </div>
    </div>
    <?php
    }

    if (!isset($_GET['page'])) {
        $pagina = 1;
    } else {
        $pagina = (int)$_GET['page'];
        if ($pagina <= 1) {
            $pagina = 1;
        }
    }

    $pagina = limpiar_cadena($pagina);
    $url = "index.php?vista=orden_de_compra_search&page=";

    $registros = 15;
    $busqueda = (isset($_SESSION['busqueda_orden_de_compra'])) ? $_SESSION['busqueda_orden_de_compra'] : '';

    require_once "./php/orden_lista.php";
    ?>
</div>
