<section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
               <h1 class="title is-medium">
                Usuarios                </h1>
				
                <h2 class="subtitle">
                   Listado completo de Usuarios.
                </h2>
			
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
<?php
        require_once "./php/main.php";

        # Eliminar usuario #
        if(isset($_GET['user_id_del'])){
            require_once "./php/usuario_eliminar.php";
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
        $url="index.php?vista=user_list&page=";
        $registros=15;
        $busqueda="";

        # Paginador usuario #
        require_once "./php/usuario_lista.php";
    ?>
</div>