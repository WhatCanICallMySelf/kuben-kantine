<?php
include_once "utils/auth.php";
if (!empty($_POST["oneTimeCode"])) {
    if ($_POST["oneTimeCode"] === $_SESSION["oneTimeCode"]) {
        login();
    } else {
        session_unset();
    }
}
if (!empty($_POST["email"])) {
    $email = $_POST["email"];
    // todo verify email

    include_once "utils/EmailClient.php";
    $oneTimeCode = strval(random_int(100000, 999999));
    $emailContent = "Din engangskode er " . $oneTimeCode;
    $emailSubject = "Kuben kantine engangskode";
    sendEmail($email, $emailContent, $emailSubject, $mail);
    $_SESSION["oneTimeCode"] = $oneTimeCode;
}
if (isLoggedIn()) {
    header("Location: /meny.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="post">
    <?php
    if (isset($_SESSION["oneTimeCode"])) { ?>
        <label for="oneTimeCode">Engangskode</label>
        <input type="number" min="100000" max="999999" name="oneTimeCode" id="oneTimeCode">
        <button type="submit">Login</button>
    <?php } else { ?>
        <label for="email">Epost</label>
        <input type="email" name="email" id="email">
        <button type="submit">Send engangskode</button>
    <?php } ?>
</form>
</body>
</html>
