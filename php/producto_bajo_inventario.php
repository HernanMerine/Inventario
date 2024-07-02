<?php
require 'productos_bajo_stock.php';
require 'generar_pdf_bajo_stock.php';
require 'envio_email_bajo_stock.php';


$productos = obtenerProductosBajoStock();


generarPDFBajoStock($productos);


enviarEmail();
