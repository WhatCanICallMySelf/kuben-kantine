<?php
//Credit til gabriel for å lage

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

// load env
require $_SERVER["DOCUMENT_ROOT"] . "/env.php";

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
//Server settings
$APP_DEBUG = false;
if ($APP_DEBUG) {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;  //Enable verbose debug output
}

$mail->isSMTP();                                            //Send using SMTP
$mail->Host = $mail_host;                     //Set the SMTP server to send through
$mail->SMTPAuth = true;                                   //Enable SMTP authentication
$mail->Username = $mail_user;                     //SMTP username
$mail->Password = $mail_password;                               //SMTP password
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
$mail->Port = $mail_port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
$mail->setFrom( $mail_from, 'Kantine system');
$mail->isHTML(true);

function sendEmail($reciver, $pdf, $mail)
{
    $mail->addAddress($reciver);
    $mail->Subject = "Meny bestilling";
    $mail->Body = "fukk deg";
    $mail->AddAttachment($pdf, "order.pdf");
    $mail->send();
}

function sendEmailTest($reciver, $content, $mail)
{
    $mail->addAddress($reciver);
    $mail->Subject = "Meny bestilling";
    $mail->Body = $content;
    $mail->send();
}
