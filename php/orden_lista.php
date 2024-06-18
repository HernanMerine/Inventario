<?php

$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";
$campos = "orden_de_compra.orden_id, orden_de_compra.total, orden_de_compra.orden_fecha, cliente.cliente_nombre, cliente.cliente_apellido, usuario.usuario_nombre, usuario.usuario_apellido";
if (isset($busqueda) && $busqueda != "") {
    $consulta_datos = "SELECT $campos FROM orden_de_compra
        JOIN 
            cliente ON orden_de_compra.cliente_id = cliente.cliente_id
        JOIN 
            usuario ON orden_de_compra.vendedor_id = usuario.usuario_id
        WHERE 
            cliente.cliente_nombre LIKE '%$busqueda%' 
            OR cliente.cliente_apellido LIKE '%$busqueda%' 
            OR usuario.usuario_nombre LIKE '%$busqueda%' 
            OR usuario.usuario_apellido LIKE '%$busqueda%' 
            OR orden_de_compra.orden_id LIKE '%$busqueda%'
        ORDER BY 
            orden_de_compra.orden_fecha DESC 
        LIMIT $inicio, $registros";
    
    $consulta_total = "SELECT COUNT(orden_de_compra.orden_id) 
        FROM 
            orden_de_compra";
} else {
    $consulta_datos = "SELECT $campos FROM orden_de_compra
        JOIN 
            cliente ON orden_de_compra.cliente_id = cliente.cliente_id
        JOIN 
            usuario ON orden_de_compra.vendedor_id = usuario.usuario_id
        ORDER BY 
            orden_de_compra.orden_fecha DESC 
        LIMIT $inicio, $registros";
    
    $consulta_total = " SELECT COUNT(orden_de_compra.orden_id) 
        FROM  orden_de_compra";
}

$conexion = conexion();
$resultado_datos = mysqli_query($conexion, $consulta_datos);
$datos = mysqli_fetch_all($resultado_datos, MYSQLI_ASSOC);

$resultado_total = mysqli_query($conexion, $consulta_total);
$total = mysqli_fetch_row($resultado_total)[0];

$Npaginas = ceil($total / $registros);

$tabla .= '
    <div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr class="has-text-centered">
                    <th>Orden ID</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
';

if ($total >= 1 && $pagina <= $Npaginas) {
    $contador = $inicio + 1;
    $pag_inicio = $inicio + 1;
    foreach ($datos as $rows) {
        $tabla .= '
            <tr class="has-text-centered">
                <td>
                   ' . $rows['orden_id'] . '
                </td>
                <td>' . $rows['total'] . '</td>
                <td>' . $rows['orden_fecha'] . '</td>
                <td>' . $rows['cliente_nombre'] . ' ' . $rows['cliente_apellido'] . '</td>
                <td>' . $rows['usuario_nombre'] . ' ' . $rows['usuario_apellido'] . '</td>
                <td>
                    <td><a href="index.php?vista=orden_detalle&orden_id=' . htmlspecialchars($rows['orden_id'], ENT_QUOTES, 'UTF-8') . '" class="button is-success is-rounded is-small">Actualizar</a></td>
                </td>
                <td>
                    <a href="' . $url . $pagina . '&order_id_del=' . $rows['orden_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
                </td>
            </tr>
        ';
        $contador++;
    }
    $pag_final = $contador - 1;
} else {
    if ($total >= 1) {
        $tabla .= '
            <tr class="has-text-centered">
                <td colspan="7">
                    <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla .= '
            <tr class="has-text-centered">
                <td colspan="7">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
}

$tabla .= '</tbody></table></div>';

if ($total > 0 && $pagina <= $Npaginas) {
    $tabla .= '<p class="has-text-right">Mostrando órdenes <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

mysqli_close($conexion);
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
?>
