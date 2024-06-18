<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Clientes</title>
</head>
<body>
  
        <section class="hero is-small">
        <div class="hero-body">
		<div class="container  pt-4 pb-4 pl-3">
                   <h1 class="title">Clientes</h1>
        <h2 class="subtitle">Completa los datos para agregar un nuevo cliente</h2>
            </div>
        </div>
    </section>
    <div class="container pb-6 pt-6 pl-5 pr-5 mb-6">
        <?php require_once "./php/main.php"; ?>
        <div class="form-rest mb-6 mt-6"></div>

        <form action="./php/cliente_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombres</label>
                        <input class="input" type="text" name="cliente_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Apellidos</label>
                        <input class="input" type="text" name="cliente_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Email</label>
                        <input class="input" type="email" name="cliente_email" maxlength="70" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Dirección</label>
                        <input class="input" type="text" name="cliente_direccion" maxlength="100" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Teléfono</label>
                        <input class="input" type="tel" name="cliente_telefono" pattern="[0-9+]{6,15}" maxlength="15" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <p class="has-text-right">
                        <button type="submit" class="button is-info is-rounded">Guardar</button>
                    </p>
                </div>
                <div class="column">
                    <p class="has-text-left">
                        <button type="button" class="button is-danger is-rounded" id="btnLimpiarCampos">Limpiar Campos</button>
                    </p>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnLimpiarCampos = document.getElementById('btnLimpiarCampos');
            const formulario = document.querySelector('.FormularioAjax');

            btnLimpiarCampos.addEventListener('click', function() {
                formulario.reset(); 
            });
        });
    </script>
</body>
</html>
