<?php
require_once "../db_connection.php";
$conn = GetDbConnection();

if (isset($_POST["update"])) {
    $sql = "UPDATE meny SET navn = ?, pris = ?, kategori = ? WHERE id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $_POST["navn"], $_POST["pris"], $_POST["kategori"], $_POST["id"]);
    $stmt->execute();
    header("Location: index.php");
}
