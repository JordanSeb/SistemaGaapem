<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluye la clase PHPMailer
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

if (isset($_POST['enviar_correo'])) {
    $destinatario = 'zamoragaapem@gmail.com';
    $asunto = 'Corte del dia';
    $cuerpo = 'Gaapem Zamora Registros del corte';

    $archivo_adjunto = $_FILES['archivo_adjunto']['tmp_name'];

    $mail = new PHPMailer(true);

    try {
        // Configura el servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Cambia esto con la configuración de tu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sebastjorian2305@gmail.com'; // Cambia esto con tu dirección de correo
        $mail->Password   = 'uckzcgewwzivuajw'; // Cambia esto con tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Usa PHPMailer::ENCRYPTION_SMTPS si tu servidor requiere SSL/TLS
        $mail->Port       = 587; // Cambia esto con el puerto de tu servidor SMTP

        // Configura el correo
        $mail->setFrom('sebastjorian2305@gmail.com', 'Sebastian Gutierrez');
        $mail->addAddress($destinatario);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;

         // Obtiene la fecha actual en la zona horaria de México
         $zonaHorariaMexico = new DateTimeZone('America/Mexico_City');
         $fechaActual = new DateTime('now', $zonaHorariaMexico);
 
         // Genera el nombre del archivo con la fecha actual
         $nombreArchivo = 'corte_del_' . $fechaActual->format("Y-m-d") . '.pdf';
 
         // Adjunta el archivo con el nuevo nombre
         $mail->addAttachment($archivo_adjunto, $nombreArchivo);
        // Envía el correo
        $mail->send();
        echo "Correo enviado con éxito.";
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>
