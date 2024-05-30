<?php
include_once "utils/auth.php";

if (!empty($_POST["oneTimeCode"])) {
    login($_POST["oneTimeCode"]);
}
if (!empty($_POST["email"])) {
    $email = $_POST["email"];
    // todo verify email propperly
    if (verifyEmail($email)) {
        include_once "utils/EmailClient.php";
        $oneTimeCode = generateOneTimeCode();
        $emailContent = "Din engangskode er " . $oneTimeCode;
        $emailSubject = "Kuben kantine engangskode";
        sendEmail($email, $emailContent, $emailSubject, $mail);
    } else {
        $error = "Ugyldig epost";
    }
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
    <?php } else {
        echo !empty($error) ? "<p>$error</p>" : "" ?>
        <label for="email">Epost</label>
        <input type="email" name="email" id="email">
        <button type="submit">Send engangskode</button>
    <?php } ?>
</form>
</body>
</html>
