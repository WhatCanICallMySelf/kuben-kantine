<?php

function createMenu(mysqli $conn): void
{
    $categories = $conn->query("SELECT * FROM kategori")->fetch_all(MYSQLI_ASSOC);
    if ($categories) {
        foreach ($categories as $category) {
            createCategory($conn, $category);
        }
    } else {
        echo "<h3>Menu is empty</h3>";
    }
}

function createCategory(mysqli $conn, array $category): void
{
    $categoryId = $category["id"];
    $categoryName = $category["navn"];
    echo "<h1>" . ucwords($categoryName) . "</h1>";
    $items = $conn->query("SELECT * FROM meny Where kategori = '$categoryId'")->fetch_all(MYSQLI_ASSOC);
    if ($items) {
        foreach ($items as $item) {
            createItem($item);
        }
    } else {
        echo "<h3>Category is empty</h3>";
    }
}

function createItem(array $item): void
{
    var_dump($item);
}

include_once "db_connection.php";
$conn = GetDbConnection();
createMenu($conn);