<?php
session_start();
$code = $_POST["code"];
$email = $_SESSION["email"];

if($code == null) {
    Header("Location: /Verify.html");
}

require "../../db_connection.php";
$result = $conn->query("SELECT * FROM users WHERE email = '$email'")->fetch_assoc();
if(time() - $result["token_timestamp"] > 300) {
    echo "koden har utløpt";
    die();
}

if ($code != $result["token"]) {
    echo "koden er feil";
    die();
}

$_SESSION["id"] = $result["id"];
if ($result["role"] == "admin") {
    $_SESSION["admin"] = true;
} else {
    $_SESSION["admin"] = false;
}
header("Location: /order.php");