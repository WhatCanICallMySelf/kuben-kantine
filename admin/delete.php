<?php
require_once "../db_connection.php";
$conn = GetDbConnection();

if (isset($_POST["delete"])) {
    $sql = "DELETE FROM meny WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_POST["id"]);
    $stmt->execute();
    header("Location: index.php");
}
