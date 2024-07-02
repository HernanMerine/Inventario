<?php
require_once '../libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function generarPDFBajoStock($productos) {
    $dompdf = new Dompdf();

    // Crear el contenido HTML
    $html = '<h1>Productos con Bajo Stock</h1>';
    $html .= '<table border="1" cellpadding="10">';
    $html .= '<tr><th>ID</th><th>Nombre</th><th>Stock</th><th>Stock Mínimo</th><th>Proveedor</th></tr>';

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

    $html .= '</table>';

    // Cargar el contenido HTML en DOMPDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Guardar el PDF en un archivo
    $pdfOutput = $dompdf->output();
    file_put_contents('productos_bajo_stock.pdf', $pdfOutput);
}
