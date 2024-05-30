<?php
session_start();
function isLoggedIn(): bool
{
    return isset($_SESSION["loggedIn"]);
}

function authRedirect(): void
{
    if (!isLoggedIn()) {
        header("Location: /");
    }
}

function login($oneTimeCode): void
{
    require $_SERVER["DOCUMENT_ROOT"] . "/env.php";
    if ($oneTimeCode === $_SESSION["oneTimeCode"]) {
        if ($oneTimeCode - time() < $oneTimeCodeTimeLimit) {
            unset($_SESSION["oneTimeCode"]);
            unset($_SESSION["oneTimeCodeTime"]);
            $_SESSION["loggedIn"] = true;
        }
    } else {
        session_unset();
    }
}

function verifyEmail($email): bool
{
    $_SESSION["email"] = $email;
    return true;
}

function generateOneTimeCode(): string
{
    $oneTimeCode = strval(random_int(100000, 999999));
    $_SESSION["oneTimeCode"] = $oneTimeCode;
    $_SESSION["oneTimeCodeTime"] = time();
    return $oneTimeCode;
}