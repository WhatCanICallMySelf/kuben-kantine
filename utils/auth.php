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

// Session structure {
//  loggedIn: bool
//  token: int
//  tokenCreationTime: int/unixtime
//}

// auto log out if logged in and token creation time is too long ago