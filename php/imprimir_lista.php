<?php
require_once "main.php";
require_once "../libreria/dompdf/autoload.inc.php";

use Dompdf\Dompdf;

// Crear instancia de Dompdf
$dompdf = new Dompdf();

// Consultar todos los registros de productos
$consulta_datos = "SELECT producto.producto_id, producto.producto_nombre, producto.producto_precio, producto.producto_stock, categoria.categoria_nombre
                   FROM producto 
                   INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id 
                   ORDER BY producto.producto_nombre ASC";

$conexion = conexion();
$datos = $conexion->query($consulta_datos);
$datos = $datos->fetch_all(MYSQLI_ASSOC);

$html = '<html>
            <head>
                <style>
                    title {
                        font: Arial;
                    }
                    .notification-custom {
                        background-color: #f0f0f0; /* Color de fondo personalizado */
                        padding: 1.5rem; /* Espaciado adicional */
                        margin: 0;
                        font-size: 1.25rem; /* Tamaño de fuente más grande */
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }
                    .notification-custom p {
                        margin-bottom: 0.5rem;
                    }
                    .table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 1rem;
                    }
                    .table thead {
                        background-color: #6a93c7; /* Color de fondo del encabezado de la tabla */
                        color: white; /* Color del texto del encabezado */
                    }
                    .table th, .table td {
                        border: 1px solid #ccc; /* Borde de celda */
                        padding: 8px; /* Espaciado interno */
                        text-align: left; /* Alineación del texto */
                    }
                    .table tfoot {
                        background-color: #aeabab; /* Color de fondo del pie de tabla */
                        font-weight: bold; /* Texto en negrita */
                    }
                    .logo {
                        max-width: 300px;
                        margin-bottom: 1rem;
                    }
                </style>
            </head>
            <body>
                <div class="notification-custom">
                    <p>Lista De precios</p>
                </div>';

$html .= '<table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoria</th>
                </tr>
            </thead>
            <tbody>';

foreach ($datos as $row) {
    $html .= '<tr>
                <td>' . $row['producto_nombre'] . '</td>
                <td>' . $row['producto_precio'] . '</td>
                <td>' . $row['producto_stock'] . '</td>
                <td>' . $row['categoria_nombre'] . '</td>
              </tr>';
}

$html .= '</tbody></table>';

$html .= '</body></html>';

$conexion->close();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("lista_productos.pdf", array("Attachment" => false));
