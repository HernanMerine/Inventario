<section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
                <h1 class="title is-medium">
                    Sistema de Inventario y Gestión de Órdenes
                </h1>
				
                <h2 class="subtitle">
                    Gestiona tu ferretería de manera eficiente y organizada
                </h2>
				<h2 class="subtitle">¡Bienvenido <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>!</h2>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="section">
        <div class="container pt-6 pb-6 pl-6 pr-6">
            <!-- Features -->
			<div class="columns">
            <?php if ($_SESSION['rol_nombre'] == 'ADMINISTRADOR') { ?>
                <div class="column">
                        <div class="box">
                            <figure class="image is-128x128">
                                <img src="./img/usuarios.png" alt="Usuarios">
                            </figure>
                            <h3 class="title is-4">Usuarios</h3>
                            <p>Gestiona los usuarios y sus roles.
                                <br></p>
                            <a href="index.php?vista=user_list" class="button is-link">Ayuda</a>
                        </div>
                    </div>
                <?php } ?>
                <div class="column">
                    <div class="box">
                        <figure class="image is-128x128">
                            <img src= "./img/clientes.png" alt="Clientes">
                        </figure>
                        <h3 class="title is-4">Clientes</h3>
                        <p>Gestiona los datos de tus clientes.<br></p>
                        <a href="index.php?vista=client_list" class="button is-link">Configuración</a>
                    </div>
                </div>
                <div class="column">
                    <div class="box">
                        <figure class="image is-128x128">
						<img src="./img/ordencompra.png"  alt="Órdenes de Compra">
                        </figure>
                        <h3 class="title is-4">Órdenes de Compra</h3>
                        <p>Gestiona las órdenes de compra de tus clientes de manera eficiente.</p>
                        <a href="index.php?vista=user_list" class="button is-link">Ver Órdenes de Compra</a>
                    </div>
                </div>


            </div>

            <!-- Additional Information -->
            <div class="columns">
                <?php if ($_SESSION['rol_nombre'] == 'ADMINISTRADOR') { ?>
                    <div class="column ">
                        <div class="box ">
                            <div class="image is-128x128 image-centered">
                                <img src="./img/inventario.png"  alt="Inventario">
                            </div>
                            <h3 class="title is-4">Inventario</h3>
                            <p>Controla y administra el inventario de tu ferretería con facilidad.</p>
                            <a href="index.php?vista=product_list" class="button is-link">Ver Inventario</a>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box">
                            <figure class="image is-128x128">
                                <img src="./img/proveedor.png" alt="Proveedores">
                            </figure>
                            <h3 class="title is-4">Proveedores</h3>
                            <p>Administra la información de tus proveedores de productos.</p>
                            <a href="index.php?vista=proveedor_list" class="button is-link">Ver Proveedores</a>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box">
                            <figure class="image is-128x128">
                                <img src="./img/reportes.png" alt="Reportes">
                            </figure>
                            <h3 class="title is-4">Reportes</h3>
                            <p>Genera reportes detallados sobre el inventario y las ordenes de compra.</p>
                            <a href="#" class="button is-link">Ver Reportes</a>
                        </div>
                    </div>
                <?php } ?>     
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>Sistema de Inventario y Gestión de Órdenes</strong> por <a href="#">Tu Empresa</a>. 
            </p>
        </div>
    </footer>