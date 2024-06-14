<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuarios</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <div class="container is-fluid mb-6">
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Nuevo usuario</h2>
    </div>
    <div class="container pb-6 pt-6 pl-5 pr-5">
        <?php require_once "./php/main.php"; ?>
        <div class="form-rest mb-6 mt-6"></div>

        <form action="./php/usuario_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data">
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombres</label>
                        <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Apellidos</label>
                        <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Usuario</label>
                        <input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Email</label>
                        <input class="input" type="email" name="usuario_email" maxlength="70">
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Clave</label>
                        <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Repetir clave</label>
                        <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                    </div>
                </div>
                <div class="column">
                    <label>Rol</label><br>
                    <div class="select is-rounded">
                        <select name="rol">
                            <option value="" selected>Seleccione una opción</option>
                            <?php
                                $conexion = conexion();

                                $rol = $conexion->query("SELECT * FROM rol");
                                if ($rol->num_rows > 0) {
                                    while ($row = $rol->fetch_assoc()) {
                                        echo '<option value="' . $row['rol_id'] . '">' . $row['nombre'] . '</option>';
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
