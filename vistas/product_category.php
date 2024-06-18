<section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
               <h1 class="title is-medium">
                  Poductos por categoría.
                </h1>
				
                <h2 class="subtitle">
                   Encontra todos los productos de una categoría.
                </h2>
			
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
        require_once "./php/main.php";
    ?>
    <div class="columns">
        <div class="column is-one-third">
            <h2 class="title has-text-centered">Categorías</h2>
            <?php
                $conexion = conexion();
                $categorias = $conexion->query("SELECT * FROM categoria");
                if ($categorias->num_rows > 0) {
                    while ($row = $categorias->fetch_assoc()) {
                        echo '<a href="index.php?vista=product_category&category_id=' . $row['categoria_id'] . '" class="button is-link is-inverted is-fullwidth">' . $row['categoria_nombre'] . '</a>';
                    }
                } else {
                    echo '<p class="has-text-centered">No hay categorías registradas</p>';
                }
                mysqli_close($conexion);
            ?>
        </div>
        <div class="column">
            <?php
                $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

                /*== Verificando categoria ==*/
                $conexion = conexion();
                $check_categoria = $conexion->query("SELECT * FROM categoria WHERE categoria_id='$categoria_id'");

                if ($check_categoria->num_rows > 0) {

                    $check_categoria = $check_categoria->fetch_assoc();

                    echo '
                        <h2 class="title has-text-centered">' . $check_categoria['categoria_nombre'] . '</h2>
                        <p class="has-text-centered pb-6">' . $check_categoria['categoria_ubicacion'] . '</p>
                    ';

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

                    $pagina = limpiar_cadena($pagina);
                    $url = "index.php?vista=product_category&category_id=$categoria_id&page="; /* <== */
                    $registros = 15;
                    $busqueda = "";

                    # Paginador producto #
                    require_once "./php/producto_lista.php";

                } else {
                    echo '<h2 class="has-text-centered title">Seleccione una categoría para empezar</h2>';
                }

            ?>
        </div>
    </div>
</div>
