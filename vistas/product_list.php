<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="path/to/bulma.min.css">
</head>
<body>
    <section class="hero is-small">
        <div class="hero-body">
            <div class="container pt-4 pb-4 pl-3">
                <h1 class="title is-medium">Inventario</h1>
                <h2 class="subtitle">Listado completo de productos.</h2>
            </div>
        </div>
    </section>

    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
        <?php
        require_once "./php/main.php";

        # Eliminar producto #
        if (isset($_GET['product_id_del'])) {
            require_once "./php/producto_eliminar.php";
        }

        if (!isset($_GET['page'])) {
            $pagina = 1;
        } else {
            $pagina = (int) $_GET['page'];
            if ($pagina <= 1) {
                $pagina = 1;
            }
        }

        $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

        $pagina = limpiar_cadena($pagina);
        $url = "index.php?vista=product_list&page="; /* <== */
        $registros = 15;
        $busqueda = "";
        require_once "./php/producto_lista.php";
        ?>
        
        <div class="field is-grouped is-grouped-right">
            <p class="control">
                <a href="php/imprimir_lista.php" class="button is-primary is-rounded" target="_blank">Imprimir lista</a>
            </p>
        </div>

    </div>