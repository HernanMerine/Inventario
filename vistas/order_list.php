  
    <section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
                 <h1 class="title">Ordenes de Compra</h1>
    <h2 class="subtitle">Listado de Ordenes</h2>
			
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
        require_once "./php/main.php";

        # Eliminar cliente #
        if(isset($_GET['client_id_del'])){
            require_once "./php/orden_eliminar.php";
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
        $url="index.php?vista=order_list&page=";
        $registros=15;
        $busqueda="";

        # Paginador cliente #
        require_once "./php/orden_lista.php";
    ?>
</div>