<?php require "./inc/session_start.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "./inc/head.php"; ?>
    </head>
    <body class="body-custom">
    <div class="main-content">
        <?php
            // Verificar si se ha solicitado una vista específica
            if(!isset($_GET['vista']) || $_GET['vista']==""){
                $_GET['vista']="welcome"; // Cambiar a welcome para que sea la página predeterminada
            }

            // Ruta para la página welcome.php
            if($_GET['vista']=="welcome"){
                include "./vistas/welcome.php"; // Incluir la página de bienvenida
            } 
            // Verificar si el archivo de vista solicitado existe y no es login o 404
            else if(is_file("./vistas/".$_GET['vista'].".php") && $_GET['vista']!="login" && $_GET['vista']!="404"){

                /*== Cerrar sesión ==*/
                if((!isset($_SESSION['id']) || $_SESSION['id']=="") || (!isset($_SESSION['usuario']) || $_SESSION['usuario']=="")){
                    include "./vistas/logout.php";
                    exit();
                }

                include "./inc/navbar.php";

                include "./vistas/".$_GET['vista'].".php";

                include "./inc/script.php";
                
            } else {
                if($_GET['vista']=="login"){
                    include "./vistas/login.php";
                } else {
                    include "./vistas/404.php";
                }
            }
        ?>
        </div>
        <footer>
            <?php
                include "./inc/footer.php";
            ?>
        </footer>
    </body>
</html>
