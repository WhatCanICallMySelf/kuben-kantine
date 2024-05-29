<?php
session_start();
function isLoggedIn()
{
    return isset($_SESSION["loggedIn"]);
}

function authRedirect()
{
    if (!isset($_SESSION["loggedIn"])) {
        header("Location: /");
    }
}

function login()
{
    unset($_SESSION["oneTimeCode"]);
    $_SESSION["loggedIn"] = true;
}
