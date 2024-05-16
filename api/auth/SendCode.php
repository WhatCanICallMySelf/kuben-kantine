<?php
session_start();

include ("../../db_connection.php");
$email = null;
if(isset($_POST["email"])) {
    $email = $_POST["email"];
}

$error = null;
if ($email == null) {
    $error = "ingen epost gitt";
}

$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query)->fetch_assoc();

if ($result == null) {
    $error = "du ligger ikke i systemet";
}

if ($error != null) {
    echo $error;
    die();
} else {
    $code = random_int(100000, 999999);
    $time = time();
    $conn->query("UPDATE users SET token = $code, token_timestamp = $time WHERE email = '$email'");
    include("../EmailClient.php");
    $mail->addAddress($email);
    $mail->Subject = "Verifikasjons kode";
    $mail->Body = "Koden er: " . $code;
    $mail->send();
    $_SESSION["email"] = $email;
    header("Location: /verify.html");
}