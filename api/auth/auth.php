<?php
session_start();
function isLoggedIn()
{
    return isset($_SESSION["id"]);
}

function authRedirect()
{
    if (!isset($_SESSION["id"])) {
        header("Location: /");
    }
}

function isAdmin()
{
    if($_SESSION["admin"]) {
        return true;
    }

    return false;
}