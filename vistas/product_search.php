<section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
               <h1 class="title is-medium">
                  Productos.
                </h1>
				
                <h2 class="subtitle">
                   Escribi el nombre del producto,  su categoria o proveedor.
                </h2>
			
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
    <?php
        require_once "./php/main.php";

        if(isset($_POST['modulo_buscador'])){
            require_once "./php/buscador.php";
        }

        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if 'busqueda_producto' is set in the session
        if(!isset($_SESSION['busqueda_producto']) || empty($_SESSION['busqueda_producto'])){
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off">
                <input type="hidden" name="modulo_buscador" value="orden_de_compra">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="Buscar producto" maxlength="50">
                    </p>
                    <p class="control">
                        <button class="button is-info is-rounded" type="submit">Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php } else { ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="producto"> 
                <input type="hidden" name="eliminar_buscador" value="producto">
                <p>Estas buscando <strong>“<?php echo $_SESSION['busqueda_producto']; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php 
    }
            // Eliminar producto
            if(isset($_GET['product_id_del'])){
                require_once "./php/producto_eliminar.php";
            }

            // Determine the page number
            $pagina = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
            if($pagina <= 1){
                $pagina = 1;
            }

            // Determine the category ID
            $categoria_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

            $pagina = limpiar_cadena($pagina);
            $url = "index.php?vista=product_search&page="; 
            $registros = 15;
            
            // Initialize 'busqueda_producto' if it is not set
            $busqueda = isset($_SESSION['busqueda_producto']) ? $_SESSION['busqueda_producto'] : '';

            // Paginador producto
            require_once "./php/producto_lista.php";
    ?>
</div>
