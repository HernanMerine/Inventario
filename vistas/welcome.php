<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockGuardian Inventory System</title>
    <link rel="stylesheet" href="./css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Gothic:wdth@75..100&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body,html {
            background-image: url('./img/fondoSG.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: #ffffff;
            height: 100%;
            min-height: 100vh;
            position: relative;
            margin-bottom: 100px;
            padding-bottom: 100px;
            overflow-y: hidden;
        }
        .is-white {
            color: #ffffff;
            font-size: 25px;
            font-weight: 500;
            padding-left: 20px;
            padding-right: 200px;
        }
        .hero {
            margin-top:330px;
            padding: 2rem 1rem;
        }
        .hero .title {
            text-align: left;
        }
        .custom-logo {
            width: 1000px; /* Ajusta según sea necesario */
            margin-bottom: 1rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .button.is-custom {
            background-color: #f77227;
            color: #fff;
            border: none;
            padding: 15px 30px;
            font-size: 2.5rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: block;
            margin: 1.5rem auto;
            font-weight: 350;
            height: 85px;

        }
        .button.is-custom:hover {
            background-color: #f75527;
        }
        .features {
            text-align: right;
            display: flex;
         padding-right:30px;
            align-items: center;
            justify-content: right;
            padding-top:50px;
        }
        .feature-item {
            list-style: none;
     
            width: 80%;
            
        }
      
        .feature-item h3 {
            font-size: 1.5em;
            margin-bottom: 2px;
            color: #f79e31;
        }
        .feature-item p {
            font-size: 1em;
            margin-bottom: 0;
            color: #ffffff;
        }
        .title-container {
            text-align: right;
            margin-top: 1.5rem;
            margin-left: 10px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <section>
        <div>
            <div class="hero">
                <a href="index.php?vista=login" class="button is-custom league-gothic">INICIAR SESIÓN</a>
                <div class="features">
                    <div class="feature-item">
                        <h3>Gestión de Inventario, Proveedores, CLientes y Usuarios</h3>
                        <p>Monitorea tus existencias en tiempo real. Controla y modifica datos de tus empleados, clientes y proveedores.</p>
                        <h3>Órdenes de Compra</h3>
                        <p>Genera y gestiona órdenes de compra fácilmente.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
