<?php
require_once "db_connection.php";
$conn = GetDbConnection();

if (isset($_POST["add"])) {
    $sql = "INSERT INTO meny (navn, pris, kategori) VALUES (?, ?, ?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $_POST["navn"], $_POST["pris"], $_POST["kategori"]);
    $stmt->execute();
    header("Location: admin.php");
}