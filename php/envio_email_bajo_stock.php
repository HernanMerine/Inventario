<?php
require '../PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function enviarEmail() {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'el.cosito.ferreteria402@gmail.com';
        $mail->Password = 'bcuy jibi ygaa zrzg';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinatarios
        $mail->setFrom('el.cosito.ferreteria402@gmail.com', 'Ferreteria El Cosito');
        $mail->addAddress('el.cosito.ferreteria402@gmail.com', 'Ferreteria El Cosito');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Lista de productos con bajo stock';
        $mail->Body    = 'Adjunto encontrarás la lista de productos con bajo stock.';
        $mail->addAttachment('productos_bajo_stock.pdf');

        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no se pudo enviar. Error: {$mail->ErrorInfo}";
    }
}
