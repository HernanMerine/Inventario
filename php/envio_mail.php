<?php
require_once('tcpdf/tcpdf.php');

// Crear el PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetFont('helvetica', '', 12);
$pdf->AddPage();
$pdf->Write(5, '¡Hola, este es un ejemplo de PDF generado con TCPDF en PHP!');
$pdfContent = $pdf->Output('ejemplo.pdf', 'S'); // Guardar el contenido del PDF en una variable

// Destinatario del correo electrónico
$to = 'destinatario@example.com';

// Asunto del correo electrónico
$subject = 'Ejemplo de PDF adjunto';

// Mensaje del correo electrónico
$message = '¡Hola! Adjunto encontrarás un ejemplo de PDF generado con TCPDF en PHP.';

// Encabezados del correo electrónico
$headers = "From: tu_email@example.com\r\n";
$headers .= "Reply-To: tu_email@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"\r\n";

// Contenido del correo electrónico
$body = "--PHP-mixed-$random_hash\r\n";
$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n";
$body .= "\r\n";
$body .= "$message\r\n";
$body .= "--PHP-mixed-$random_hash\r\n";
$body .= "Content-Type: application/pdf; name=\"ejemplo.pdf\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment\r\n";
$body .= "\r\n";
$body .= chunk_split(base64_encode($pdfContent));
$body .= "--PHP-mixed-$random_hash--";

// Envío del correo electrónico
if (mail($to, $subject, $body, $headers)) {
    echo 'El correo electrónico se envió correctamente.';
} else {
    echo 'Hubo un problema al enviar el correo electrónico.';
}
?>
