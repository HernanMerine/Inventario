<section class="hero is-small">
    <div class="hero-body">
    <div class="container  pt-4 pb-4 pl-3">
            <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Listado de categorías</h2>
        </div>
    </div>
</section>

<div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
        require_once "./php/main.php";

        # Eliminar categoria #
        if(isset($_GET['category_id_del'])){
            require_once "./php/categoria_eliminar.php";
        }

        if(!isset($_GET['page'])){
            $pagina=1;
        }else{
            $pagina=(int) $_GET['page'];
            if($pagina<=1){
                $pagina=1;
            }
        }

        $pagina=limpiar_cadena($pagina);
        $url="index.php?vista=category_list&page="; /* <== */
        $registros=15;
        $busqueda="";

        # Paginador categoria #
        require_once "./php/categoria_lista.php";
    ?>
</div>