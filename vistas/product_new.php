<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Productos</title>
</head>
<body>
    <div class="container is-fluid mb-6">
        <h1 class="title">Productos</h1>
        <h2 class="subtitle">Nuevo producto</h2>
    </div>
    <div class="container pb-6 pt-6 pl-5 pr-5">
        <?php require_once "./php/main.php"; ?>
        <div class="form-rest mb-6 mt-6"></div>

        <form action="./php/producto_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombre</label>
                        <input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" required>
                    </div>
                </div>
                <div class="column">
                    <label>Proveedor</label><br>
                    <div class="select is-rounded">
                        <select name="producto_proveedor">
                            <option value="" selected>Seleccione una opción</option>
                            <?php
                                $conexion = conexion();

                                $proveedor = $conexion->query("SELECT * FROM proveedor");
                                if ($proveedor->num_rows > 0) {
                                    while ($row = $proveedor->fetch_assoc()) {
                                        echo '<option value="' . $row['proveedor_id'] . '">' . $row['proveedor_nombre'] . '</option>';
                                    }
                                }
                                $conexion->close();
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Costo</label>
                        <input class="input" type="text" name="producto_costo" pattern="[0-9.]{1,25}" maxlength="25" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Porcentaje de ganancia</label>
                        <input class="input" type="text" name="producto_porcentaje" pattern="[0-9.]{1,25}" maxlength="25" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Stock</label>
                        <input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required>
                    </div>
                </div>
                <div class="column">
                    <label>Categoría</label><br>
                    <div class="select is-rounded">
                        <select name="producto_categoria">
                            <option value="" selected>Seleccione una opción</option>
                            <?php
                                $conexion = conexion();

                                $categorias = $conexion->query("SELECT * FROM categoria");
                                if ($categorias->num_rows > 0) {
                                    while ($row = $categorias->fetch_assoc()) {
                                        echo '<option value="' . $row['categoria_id'] . '">' . $row['categoria_nombre'] . '</option>';
                                    }
                                }
                                $conexion->close();
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <label>Foto o imagen del producto</label><br>
                    <div class="file is-small has-name">
                        <label class="file-label">
                            <input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg">
                            <span class="file-cta">
                                <span class="file-label">Imagen</span>
                            </span>
                            <span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <p class="has-text-centered">
                        <button type="submit" class="button is-info is-rounded">Guardar</button>
                    </p>
                </div>
                <div class="column">
                    <p class="has-text-centered">
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
