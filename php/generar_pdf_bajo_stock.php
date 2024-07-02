<?php
require_once '../libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function generarPDFBajoStock($productos) {
    $dompdf = new Dompdf();

    // Crear el contenido HTML con estilos CSS
    $html = '<html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                        }
                        h1 {
                            text-align: center;
                            color: #333;
                        }
                        .table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 1rem;
                        }
                        .table thead {
                            background-color: #6a93c7;
                            color: white;
                        }
                        .table th, .table td {
                            border: 1px solid #ccc;
                            padding: 8px;
                            text-align: left;
                        }
                        .table tbody tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }
                    </style>
                </head>
                <body>
                    <h1>Productos con Bajo Stock</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Stock</th>
                                <th>Stock Mínimo</th>
                                <th>Proveedor</th>
                            </tr>
                        </thead>
                        <tbody>';

    // Añadir filas para cada producto
    foreach ($productos as $producto) {
        $html .= '<tr>';
        $html .= '<td>' . $producto['producto_id'] . '</td>';
        $html .= '<td>' . $producto['producto_nombre'] . '</td>';
        $html .= '<td>' . $producto['producto_stock'] . '</td>';
        $html .= '<td>' . $producto['producto_stock_minimo'] . '</td>';
        $html .= '<td>' . $producto['proveedor_nombre'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>
                </body>
            </html>';

    // Cargar el contenido HTML en DOMPDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Guardar el PDF en un archivo
    $pdfOutput = $dompdf->output();
    file_put_contents('productos_bajo_stock.pdf', $pdfOutput);
}
