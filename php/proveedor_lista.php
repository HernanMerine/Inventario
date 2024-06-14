<?php
ob_start();
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

$campos = "proveedor.proveedor_id, proveedor.proveedor_nombre, proveedor.proveedor_telefono,proveedor.proveedor_vendedor,proveedor.proveedor_mail,proveedor.proveedor_direccion";

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
    
    $tabla .= '<div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth is-narrow">
             <thead>
                 <tr class="has-text-centered">
                     <th>ID</th>
                     <th>Nombre</th>
                     <th>Mail</th>
                     <th>Telefono</th>
                     <th>Vendedor</th>
                     <th>Direccion</th>
                     <th colspan="2">Opciones</th>
                 </tr>
             </thead>
             <tbody>';
    
    foreach ($datos as $row) {
        $tabla .= '<tr class="has-text-centered">
             <td>'. $row['proveedor_id'] .'</td>
             <td>' . $row['proveedor_nombre'] . '</td>
             <td>' .$row['proveedor_mail']. '</td>
             <td>' . $row['proveedor_telefono'] . '</td>
             <td>' .$row['proveedor_vendedor']. '</td>
             <td>' .$row['proveedor_direccion']. '</td>
             <td> <a href="index.php?vista=proveedor_update&proveedor_id_up=' . $row['proveedor_id'] . '" class="button is-success is-rounded is-small">Actualizar</a></td>
             <td><a href="' . $url . $pagina . '&proveedor_id_del=' . $row['proveedor_id'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
        </td>
         </tr>';
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

$conexion->close();
echo $tabla;

if ($total > 0 && $pagina <= $Npaginas) {
    echo '</tbody></table>'; // Cierre de la tabla
    echo '<p class="has-text-right">Mostrando proveedores <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
ob_end_flush();
?>
