<?php
ob_start();
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

$campos = "producto.producto_id,producto.producto_nombre,producto.producto_precio,producto.producto_stock,producto.producto_foto";

if (isset($busqueda) && $busqueda != "") {
    $consulta_datos = "SELECT $campos FROM producto LIKE '%$busqueda%' ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";
    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_nombre LIKE '%$busqueda%'";
} elseif ($categoria_id > 0) {
    $consulta_datos = "SELECT $campos FROM producto WHERE producto.categoria_id='$categoria_id' ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";
    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE categoria_id='$categoria_id'";
} else {
    $consulta_datos = "SELECT $campos FROM producto ORDER BY producto.producto_nombre ASC LIMIT $inicio,$registros";
    $consulta_total = "SELECT COUNT(producto_id) FROM producto";
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
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Unidades</th>
                            <th colspan="2">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($datos as $row) {
        $imagen = (is_file("./img/producto/" . $row['producto_foto'])) ? $row['producto_foto'] : 'producto.jpeg';
        $imagen_html = '<img src="./img/producto/' . $imagen . '" style="max-width: 80px;">';

        $tabla .= '<tr class="has-text-centered">
                        <td>'.$imagen_html. '</td>
                        <td>' . $row['producto_nombre'] . '</td>
                        <td>' . $row['producto_precio'] . '</td>
                        <td>' . $row['producto_stock'] . '</td>
                        <td> <input class="input" type="number" min="1" name="units" placeholder="Unidades"></td>
                        <td> <button class="button is-success is-rounded is-small" name="add_to_order">Agregar</button></td>
                        <td> <button class="button is-success is-rounded is-small" name="add_to_order">Eliminar</button></td>';
        $contador++;
    }

    $pag_final = $contador - 1;
}

if ($total > 0 && $pagina <= $Npaginas) {
    $tabla .= '</tbody></table>'; // Cierre de la tabla
    $tabla .= '<p class="has-text-right">Mostrando productos <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion->close();
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
    echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
ob_end_flush();
?>
