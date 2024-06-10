<div class="container is-fluid mb-6">
    <h1 class="title">Proveedores</h1>
    <h2 class="subtitle">Lista de proveedores</h2>
</div>

<div class="container pb-6 pt-6">  
    <?php
        require_once "./php/main.php";

        # Eliminar proveedor #
        if(isset($_GET['proveedor_id_del'])){
            require_once "./php/proveedor_eliminar.php";
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
        $url="index.php?vista=proveedor_list&page=";
        $registros=15;
        $busqueda="";

        # Paginador proveedor #
        require_once "./php/proveedor_lista.php";
    ?>
</div>