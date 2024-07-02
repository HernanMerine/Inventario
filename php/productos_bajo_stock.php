<?php
require_once 'main.php';

function obtenerProductosBajoStock() {
    // Conectar a la base de datos
    $conexion = conexion();

    // Definir la consulta SQL con JOIN
    $sql = "SELECT 
                p.producto_id, 
                p.producto_nombre, 
                p.producto_stock, 
                p.producto_stock_minimo,
                pr.proveedor_nombre 
            FROM producto p
            JOIN proveedor pr ON p.proveedor_id = pr.proveedor_id
            WHERE p.producto_stock <= p.producto_stock_minimo";

    // Ejecutar la consulta
    $result = $conexion->query($sql);

    // Crear un array para almacenar los resultados
    $productos = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }

    // Cerrar la conexión
    $conexion->close();
    return $productos;
}
