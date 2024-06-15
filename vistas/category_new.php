<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Categorías</title>
</head>
<body>
    <div class="container is-fluid mb-6">
        <h1 class="title">Categorías</h1>
        <h2 class="subtitle">Nueva categoría</h2>
    </div>

    <div class="container pb-6 pt-6 pl-5 pr-5">
        <div class="form-rest mb-6 mt-6"></div>

        <form action="./php/categoria_guardar.php" method="POST" class="FormularioAjax" autocomplete="off">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombre</label>
                        <input class="input" type="text" name="categoria_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Ubicación</label>
                        <input class="input" type="text" name="categoria_ubicacion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" maxlength="150">
                    </div>
                </div>
            </div>
            <p class="has-text-centered">
                <button type="submit" class="button is-info is-rounded">Guardar</button>
                <button type="button" class="button is-danger is-rounded" id="btnLimpiarCampos">Limpiar Campos</button>
            </p>
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
