
<?php
require './PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/PHPMailer-master/src/SMTP.php';
require './PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmailConPDF($emailCliente, $pdf, $cliente_nombre_completo) {
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
    $mail->addAddress($emailCliente, $cliente_nombre_completo);
    $mail->Subject = 'holi';
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
            <div class="header">
                <h1>Gracias por tu compra</h1>
            </div>
            <div class="content">
                <h2>Hola!</h2>
                <p>Gracias por tu compra.Te dejamos adjunto el detalle de tu orden. Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos.</p>
                <p>Nos esforzamos por ofrecer el mejor servicio posible y esperamos que disfrutes de tu compra.</p>
                <p>Nos vemos la proxima!<br>El Cosito Ferretería</p>
            </div>
            <div class="footer">
                <p>&copy; ' . date("Y") . ' El Cosito Ferretería. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    $mail->addStringAttachment($pdf,'orden_de_compra.pdf');

    return $mail->send();
}


function enviarEmailConPDF2($emailCliente, $pdf) {
    ini_set('SMTP', 'smtp.gmail.com');
    ini_set('smtp_port', '587');
    $from = "el.cosito.ferreteria402@gmail.com";
    $nombre = "Ferreteria El Cosito";
    $subject = "--Detalle de tu Orden--";
    $destino=$emailCliente;
    // Contenido del correo
    $htmlContent = '
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
            <div class="header">
                <h1>Gracias por tu compra</h1>
            </div>
            <div class="content">
                <h2>Hola,</h2>
                <p>Gracias por tu compra.Te dejamos adjunto el detalle de tu orden. Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos.</p>
                <p>Nos esforzamos por ofrecer el mejor servicio posible y esperamos que disfrutes de tu compra.</p>
                <p>Nos vemos la proxima!<br>El Cosito Ferretería</p>
            </div>
            <div class="footer">
                <p>&copy; ' . date("Y") . ' El Cosito Ferretería. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    ';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n"; 
    //dirección del remitente
    $headers .= "From: $nombre <$from>\r\n"; 
    //ruta del mensaje desde origen a destino
    $headers .= "Return-path: <$destino>\r\n"; 
  
    // Encabezados del correo
    $mime_boundary = md5(time());
    $message = "--{$mime_boundary}\n" .
               "Content-Type: text/html; charset=\"UTF-8\"\n" .
               "Content-Transfer-Encoding: 7bit\n\n" .
               $htmlContent . "\n\n";

    // Preparando el archivo adjunto
    $pdfContent = chunk_split(base64_encode($pdf));
    $message .= "--{$mime_boundary}\n" .
                "Content-Type: application/pdf;\n" .
                " name=\"orden.pdf\"\n" .
                "Content-Disposition: attachment;\n" .
                " filename=\"orden.pdf\"\n" .
                "Content-Transfer-Encoding: base64\n\n" .
                $pdfContent . "\n\n" .
                "--{$mime_boundary}--";

    // Enviar el correo
    $mail = mail($destino, $subject,  $message, $headers); 

    if ($mail) {
        echo 'El mensaje ha sido enviado';
    } else {
        echo 'El mensaje no pudo ser enviado';
    }
}
?>