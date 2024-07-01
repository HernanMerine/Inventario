<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

// Establecer la conexión a la base de datos
$conexion = conexion();

$campos = "proveedor.proveedor_id, proveedor.proveedor_nombre, proveedor.proveedor_mail, proveedor.proveedor_telefono, proveedor.proveedor_vendedor, proveedor.proveedor_direccion";

if (isset($busqueda) && $busqueda != "") {
    $busqueda = $conexion->real_escape_string($busqueda);
    $consulta_datos = "SELECT $campos FROM proveedor 
    WHERE (proveedor_nombre LIKE '%$busqueda%' OR proveedor_mail LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' 
    OR proveedor_vendedor LIKE '%$busqueda%' OR proveedor_direccion LIKE '%$busqueda%') 
    ORDER BY proveedor_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(proveedor_id) FROM proveedor 
    WHERE (proveedor_nombre LIKE '%$busqueda%' OR proveedor_mail LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' 
    OR proveedor_vendedor LIKE '%$busqueda%' OR proveedor_direccion LIKE '%$busqueda%')";
} else {
    $consulta_datos = "SELECT $campos FROM proveedor ORDER BY proveedor_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(proveedor_id) FROM proveedor";
}

// Realizar las consultas
$datos = $conexion->query($consulta_datos);
$total = $conexion->query($consulta_total);

if ($datos && $total) {
    $total = (int)$total->fetch_assoc()['COUNT(proveedor_id)'];
    $Npaginas = ceil($total / $registros);

    $tabla .= '<div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr class="has-text-centered">
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Vendedor</th>
                                <th>Dirección</th>
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
                            <td>' . $row['proveedor_nombre'] . '</td>
                            <td>' . $row['proveedor_mail'] . '</td>
                            <td>' . $row['proveedor_telefono'] . '</td>
                            <td>' . $row['proveedor_vendedor'] . '</td>
                            <td>' . $row['proveedor_direccion'] . '</td>
                            <td><a href="index.php?vista=proveedor_update&proveedor_id_up=' . $row['proveedor_id'] . '" class="button is-success is-rounded is-small">Actualizar</a></td>
                            <td><a href="' . $url . $pagina . '&proveedor_id_del=' . $row['proveedor_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a></td>
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
        $tabla .= '<p class="has-text-right">Mostrando proveedores <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
    }
} else {
    $tabla .= '<p class="has-text-centered">Error al cargar los datos</p>';
}

$conexion->close();
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
