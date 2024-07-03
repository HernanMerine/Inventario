
<?php
require './PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/PHPMailer-master/src/SMTP.php';
require './PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmailPedidoConPDF($clientEmail, $pdf, $proveedor_nombre) {
  
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'el.cosito.ferreteria402@gmail.com';
        $mail->Password = 'bcuy jibi ygaa zrzg';
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAutoTLS = false;
        $mail->Port = 587;
        $mail->setFrom('el.cosito.ferreteria402@gmail.com', 'Ferreteria El Cosito');
        $mail->addAddress($clientEmail, $proveedor_nombre);
        $mail->Subject = 'PEDIDO DE PRODUCTOS';
        $mail->isHTML(true);
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    padding: 20px 0;
                    background-color: #007BFF;
                    color: #ffffff;
                    border-top-left-radius: 8px;
                    border-top-right-radius: 8px;
                }
                .header h1 {
                    margin: 0;
                }
                .content {
                    padding: 20px;
                }
                .content h2 {
                    color: #333333;
                }
                .content p {
                    color: #666666;
                    line-height: 1.6;
                }
                .footer {
                    text-align: center;
                    padding: 10px 0;
                    background-color: #007BFF;
                    color: #ffffff;
                    border-bottom-left-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
            </style>
        </head>
        <body>
            <div class="container">
              
                <div class="content">
                    <h2>Hola!</h2>
                    <p>Adjunto el pdf con la lista de productos solicitados</p>
                    <p>El Cosito Ferretería</p>
                </div>
               
            </div>
        </body>
        </html>
        ';
        $mail->addStringAttachment($pdf,'pedido_proveedor.pdf');
    
        return $mail->send();
    }


?>