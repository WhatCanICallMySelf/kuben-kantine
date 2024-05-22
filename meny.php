<?php
use Dompdf\Dompdf;
require 'vendor/autoload.php';

function createMenu(mysqli $conn, $input = true, $selectedCategory = null): void
{
    $categories = $conn->query("SELECT * FROM kategori")->fetch_all(MYSQLI_ASSOC);
    if ($categories) {
        foreach ($categories as $category) {
            if ($selectedCategory === null || $category['id'] == $selectedCategory) {
                createCategory($conn, $category, $input);
            }
        }
    } else {
        echo "<h3>Menu is empty</h3>";
    }
}

function createCategory(mysqli $conn, array $category, $input): void
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
    $items = $conn->query("SELECT * FROM meny WHERE kategori = '$categoryId'")->fetch_all(MYSQLI_ASSOC);
    if ($items) {
        foreach ($items as $item) {
            createItem($item, $input);
        }
    } else {
        echo "<tr><td></td><td><p>Kategorien er tom</p></td><td></td><td></td></tr>";
    }
    echo "</tbody></table>";
}

function createItem(array $item, $input): void
{
    $itemId = $item["id"];
    $itemName = $item["navn"];
    $itemPrice = $item["pris"];
    $value = $_POST[$itemId] ?? "";
    echo "<tr>";
    echo "<td><p>" . $itemId . "</p></td>";
    echo "<td><p>" . ucwords($itemName) . "</p></td>";
    echo "<td><p>" . $itemPrice . "</p></td>";
    if ($input) {
        echo "<td><input name='$itemId' type='number' min='0' value='$value'></td>";
    } else {
        echo "<td><p>$input</p></td>";
    }
    echo "</tr>";
}

include_once "db_connection.php";
$conn = GetDbConnection();
include_once "api/EmailClient.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_pdf'])) {
    $file = 'test.html';
    $html = file_get_contents($file);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream();
    $pdf = $dompdf->output();
    file_put_contents("menu.pdf", $pdf);
    sendEmail("email", "menu.pdf", $mail);
}

$selectedCategory = $_GET['category'] ?? null;
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
    <div class="center">
        <div class="oppskrift_kategorier">
            <div class="space_between">
                <?php
                $kategoriQuery = "SELECT * FROM kategori";
                $categories = $conn->query($kategoriQuery)->fetch_all(MYSQLI_ASSOC);

                if (empty($categories)) {
                    echo "<p>Fant ikke noen kategorier</p>";
                } else {
                    echo "<div class='matkategori' data-kategori-id='alle' onclick='filterCategory(null)'>Alle kategorier</div>";
                    foreach ($categories as $kategori) {
                        $kategori_id = $kategori['id'];
                        $kategori_navn = $kategori['navn'];
                        $selectedClass = ($selectedCategory == $kategori_id) ? 'selected' : '';
                        echo "<div class='matkategori $selectedClass' data-kategori-id='$kategori_id' onclick='filterCategory($kategori_id)'>$kategori_navn</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <form method='post'>
        <?php
        createMenu($conn, true, $selectedCategory);
        ?>
        <button type='submit' name='generate_pdf'>Submit</button>
    </form>
</main>

<script>
    function filterCategory(categoryId) {
        let url = new URL(window.location.href);
        if (categoryId) {
            url.searchParams.set('category', categoryId);
        } else {
            url.searchParams.delete('category');
        }
        window.location.href = url.toString();
    }
</script>
</body>
</html>
