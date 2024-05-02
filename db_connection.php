<?php
function GetDbConnection(): false|mysqli
{
    include_once "env.php";
    try {
        return mysqli_connect($hostname, $user, $password, $db);
    } catch (mysqli_sql_exception $exception) {
        if ($exception->getCode() === 1049) {
            var_dump($exception);
            die("<p>Databasen mangler</p>");
        } else {
            die("<p>Noe gikk galt Error: $exception</p>");
        }
    }
}
