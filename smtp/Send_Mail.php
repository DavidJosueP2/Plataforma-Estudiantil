<?php

// Asegúrate de incluir el autoload de Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function Send_Mail($to, $subject, $body)
{
    $from = "lsdmk40@gmail.com";  // Cambia esto con tu dirección de correo
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP de Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'lsdmk40@gmail.com'; // Tu dirección de correo Gmail
        $mail->Password   = 'diyy pzwm vkkm vjrx'; // Tu contraseña de correo Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Configuración adicional
        $mail->setFrom($from, 'FISEI');
        $mail->addReplyTo($from, 'FISEI');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->addAddress($to, $to);
        $mail->CharSet = 'UTF-8'; // Añade esta línea para establecer la codificación

        // Envío del correo
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error en el envío del correo: {$mail->ErrorInfo}";
    }
}

?>
