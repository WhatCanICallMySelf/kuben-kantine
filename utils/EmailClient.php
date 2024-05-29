<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//Load Composer's autoloader
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
// load env
require $_SERVER["DOCUMENT_ROOT"] . "/env.php";

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                //Enable verbose debug output

$mail->isSMTP();                                      //Send using SMTP
$mail->Host = $mail_host;                             //Set the SMTP server to send through
$mail->SMTPAuth = true;                               //Enable SMTP authentication
$mail->Username = $mail_user;                         //SMTP username
$mail->Password = $mail_password;                     //SMTP password
//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      //Enable implicit TLS encryption
$mail->Port = $mail_port;                             //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
$mail->setFrom($mail_from, 'Kantine system');
$mail->isHTML(true);

function sendPDFEmail($receiver, $pdf, PHPMailer $mail): bool
{
    try {
        $mail->addAddress($receiver);
        $mail->Subject = "Meny bestilling";
        $mail->Body = "Her er din meny bestilling";
        $mail->AddAttachment($pdf, "meny.pdf");
        $mail->send();
        return true;
    } catch (Exception $exception) {
        error_log($exception);
        return false;
    }

}

function sendEmail($receiver, $content,$subject, PHPMailer $mail): bool
{
    try {
        $mail->addAddress($receiver);
        $mail->Subject = $subject;
        $mail->Body = $content;
        $mail->send();
        return true;
    } catch (Exception $exception) {
        error_log($exception);
        return false;
    }
}
