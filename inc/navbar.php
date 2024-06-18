<nav class="navbar is-dark" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="index.php?vista=home">
            <img src="./img/cosito.png" width="150" height="110">
        </a>
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">

            <?php if ($_SESSION['rol_nombre'] == 'ADMINISTRADOR') { ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Usuarios</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=user_new" class="navbar-item">Nuevo</a>
                        <a href="index.php?vista=user_list" class="navbar-item">Lista de Usuarios</a>
                        <a href="index.php?vista=user_search" class="navbar-item">Buscar</a>
                    </div>
                </div>
            <?php } ?>

            <?php if ($_SESSION['rol_nombre'] == 'ADMINISTRADOR' || $_SESSION['rol_nombre'] == 'VENDEDOR') { ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Clientes</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=client_new" class="navbar-item">Agregar Cliente</a>
                        <a href="index.php?vista=client_list" class="navbar-item">Lista de Clientes</a>
                        <a href="index.php?vista=client_search" class="navbar-item">Buscar</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Órdenes de compra</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=order_new" class="navbar-item">Crear Nueva Orden</a>
                        <a href="index.php?vista=order_list" class="navbar-item">Ordenes de Compra</a>
                        <a href="index.php?vista=order_search" class="navbar-item">Buscar</a>
                    </div>
                </div>
            <?php } ?>

            <?php if ($_SESSION['rol_nombre'] == 'ADMINISTRADOR') { ?>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Proveedores</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=proveedor_new" class="navbar-item">Nuevo Proveedor</a>
                        <a href="index.php?vista=proveedor_list" class="navbar-item">Lista de Proveedores</a>
                        <a href="index.php?vista=proveedor_search" class="navbar-item">Buscar</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Categorías</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=category_new" class="navbar-item">Agregar Categoría</a>
                        <a href="index.php?vista=category_list" class="navbar-item">Todas las Categorías</a>
                        <a href="index.php?vista=category_search" class="navbar-item">Buscar</a>
                    </div>
                </div>

                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">Productos</a>
                    <div class="navbar-dropdown">
                        <a href="index.php?vista=product_new" class="navbar-item">Ingresar Producto</a>
                        <a href="index.php?vista=product_list" class="navbar-item">Inventario Completo</a>
                        <a href="index.php?vista=product_category" class="navbar-item">Productos por Caterogría</a>
                        <a href="index.php?vista=product_search" class="navbar-item">Buscar</a>
                    </div>
                </div>
            <?php } ?>

        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a href="index.php?vista=user_update&user_id_up=<?php echo $_SESSION['id']; ?>" class="button is-primary is-rounded">
                        Mi cuenta
                    </a>
                    <a href="index.php?vista=logout" class="button is-link is-rounded">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
