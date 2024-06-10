<?php
ob_start();
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

$campos = "proveedor.proveedor_id, proveedor.nombre, proveedor.contacto";

if (isset($busqueda) && $busqueda != "") {

    $consulta_datos = "SELECT $campos FROM proveedor WHERE proveedor.nombre LIKE '%$busqueda%' ORDER BY proveedor.nombre ASC LIMIT $inicio,$registros";

    $consulta_total = "SELECT COUNT(proveedor_id) FROM proveedor WHERE nombre LIKE '%$busqueda%'";

} else {

    $consulta_datos = "SELECT $campos FROM proveedor ORDER BY proveedor.nombre ASC LIMIT $inicio,$registros";

    $consulta_total = "SELECT COUNT(proveedor_id) FROM proveedor";
}

$conexion = conexion();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetch_all(MYSQLI_ASSOC);

$total = $conexion->query($consulta_total);
$total = (int) $total->fetch_row()[0];

$Npaginas = ceil($total / $registros);

if ($total >= 1 && $pagina <= $Npaginas) {
    $contador = $inicio + 1;
    $pag_inicio = $inicio + 1;
    foreach ($datos as $rows) {
        $tabla .= '
            <article class="media">
                <div class="media-content">
                    <div class="content">
                      <p>
                        <strong>' . $contador . ' - ' . $rows['nombre'] . '</strong><br>
                        <strong>CONTACTO:</strong> ' . $rows['contacto'] . '
                      </p>
                    </div>
                    <div class="has-text-right">
                        <a href="index.php?vista=proveedor_update&proveedor_id_up=' . $rows['proveedor_id'] . '" class="button is-success is-rounded is-small">Actualizar</a>
                        <a href="' . $url . $pagina . '&proveedor_id_del=' . $rows['proveedor_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
                    </div>
                </div>
            </article>
            <hr>
        ';
        $contador++;
    }
    $pag_final = $contador - 1;
} else {
    if ($total >= 1) {
        $tabla .= '
            <p class="has-text-centered" >
                <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic acá para recargar el listado
                </a>
            </p>
        ';
    } else {
        $tabla .= '
            <p class="has-text-centered" >No hay registros en el sistema</p>
        ';
    }
}

if ($total > 0 && $pagina <= $Npaginas) {
    $tabla .= '<p class="has-text-right">Mostrando proveedores <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion->close();
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
ob_end_flush();
