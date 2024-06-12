<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

// Establecer la conexión a la base de datos
$conexion = conexion();

$campos = "cliente.cliente_id, cliente.cliente_nombre, cliente.cliente_apellido, cliente.cliente_email, cliente.cliente_direccion, cliente.cliente_telefono";

if (isset($busqueda) && $busqueda != "") {
    $busqueda = $conexion->real_escape_string($busqueda);
    $consulta_datos = "SELECT $campos FROM cliente WHERE (cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_email LIKE '%$busqueda%' OR cliente_direccion LIKE '%$busqueda%' OR cliente_telefono LIKE '%$busqueda%') ORDER BY cliente_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(cliente_id) FROM cliente WHERE (cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_email LIKE '%$busqueda%' OR cliente_direccion LIKE '%$busqueda%' OR cliente_telefono LIKE '%$busqueda%')";
} else {
    $consulta_datos = "SELECT $campos FROM cliente ORDER BY cliente_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(cliente_id) FROM cliente";
}

// Realizar las consultas
$datos = $conexion->query($consulta_datos);
$total = $conexion->query($consulta_total);

if ($datos && $total) {
    $total = (int)$total->fetch_assoc()['COUNT(cliente_id)'];
    $Npaginas = ceil($total / $registros);

    $tabla .= '<div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr class="has-text-centered">
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Email</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>';

    if ($total >= 1 && $pagina <= $Npaginas) {
        $contador = $inicio + 1;
        $pag_inicio = $inicio + 1;

        while ($row = $datos->fetch_assoc()) {
            $tabla .= '<tr class="has-text-centered">
                            <td>' . $contador . '</td>
                            <td>' . $row['cliente_nombre'] . '</td>
                            <td>' . $row['cliente_apellido'] . '</td>
                            <td>' . $row['cliente_email'] . '</td>
                            <td>' . $row['cliente_direccion'] . '</td>
                            <td>' . $row['cliente_telefono'] . '</td>
                            <td><a href="index.php?vista=client_update&cliente_id_up=' . $row['cliente_id'] . '" class="button is-success is-rounded is-small">Actualizar</a></td>
                            <td><a href="' . $url . $pagina . '&client_id_del=' . $row['cliente_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a></td>
                        </tr>';
            $contador++;
        }
        $pag_final = $contador - 1;
    } else {
        if ($total >= 1) {
            $tabla .= '<tr class="has-text-centered">
                            <td colspan="8">
                                <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">Haga clic acá para recargar el listado</a>
                            </td>
                        </tr>';
        } else {
            $tabla .= '<tr class="has-text-centered">
                            <td colspan="8">No hay registros en el sistema</td>
                        </tr>';
        }
    }

    $tabla .= '</tbody></table></div>';

    if ($total > 0 && $pagina <= $Npaginas) {
        $tabla .= '<p class="has-text-right">Mostrando clientes <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
    }
} else {
    $tabla .= '<p class="has-text-centered">Error al cargar los datos</p>';
}

$conexion->close();
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
