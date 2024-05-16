<?php

function createMenu(mysqli $conn): void
{
    $categories = $conn->query("SELECT * FROM kategori")->fetch_all(MYSQLI_ASSOC);
    if ($categories) {
        echo "<form method='post'>";
        foreach ($categories as $category) {
            createCategory($conn, $category);
        }
        echo "<button type='submit'>Submit</button>";
        echo "</form>";
    } else {
        echo "<h3>Menu is empty</h3>";
    }
}

function createCategory(mysqli $conn, array $category): void
{
    $categoryId = $category["id"];
    $categoryName = $category["navn"];
    echo "<table><thead><tr><th colspan='4'>";
    echo "<p>" . ucwords($categoryName) . "</p></th></tr><tr>";
    echo "<th><p>Meny nr.</p></th>";
    echo "<th><p>Meny</p></th>";
    echo "<th><p>Pris</p></th>";
    echo "<th><p>Antall</p></th>";
    echo "</tr></thead><tbody>";
    $items = $conn->query("SELECT * FROM meny Where kategori = '$categoryId'")->fetch_all(MYSQLI_ASSOC);
    if ($items) {
        foreach ($items as $item) {
            createItem($item);
        }
    } else {
        echo "<r><td></td><td><p>Kategorien er tom</p></td><td></td><td></td></r>";
    }
    echo "</tbody></table>";
}

function createItem(array $item): void
{
    $itemId = $item["id"];
    $itemName = $item["navn"];
    $itemPrice = $item["pris"];
    echo "<tr>";
    echo "<td><p>" . $itemId . "</p></td>";
    echo "<td><p>" . ucwords($itemName) . "</p></td>";
    echo "<td><p>" . $itemPrice . "</p></td>";
    echo "<td><input name='$itemId' type='number' min='0'></td>";
    echo "</tr>";
}

include_once "db_connection.php";
$conn = GetDbConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// DO PDF STUFF
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Meny</title>
</head>
<body>
<main>
    <?php createMenu($conn); ?>
</main>
</body>
</html>
