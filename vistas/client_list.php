<section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
              <h1 class="title">Clientes</h1>
    <h2 class="subtitle">Lista de clientes</h2>
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
        require_once "./php/main.php";

        # Eliminar cliente #
        if(isset($_GET['client_id_del'])){
            require_once "./php/cliente_eliminar.php";
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
        $url="index.php?vista=client_list&page=";
        $registros=15;
        $busqueda="";

        # Paginador cliente #
        require_once "./php/cliente_lista.php";
    ?>
</div>