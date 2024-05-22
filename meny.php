<?php
use Dompdf\Dompdf;
require 'vendor/autoload.php';
function createMenu(mysqli $conn, $input = true): void
{
    $categories = $conn->query("SELECT * FROM kategori")->fetch_all(MYSQLI_ASSOC);
    if ($categories) {
        foreach ($categories as $category) {
            createCategory($conn, $category, $input);
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
    $items = $conn->query("SELECT * FROM meny Where kategori = '$categoryId'")->fetch_all(MYSQLI_ASSOC);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate html for conversion to pdf
//    $html = '<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>Meny</title><style>';
//    $html .= file_get_contents("style.css");
//    $html .= '</style></head><body><main>';
//    ob_start();
//    createMenu($conn);
//    $html .= ob_get_clean();
//    $html .= "</main></body></html>";
    $file = 'test.html';
//    file_put_contents($file, $html);
    $html = file_get_contents($file);

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    // Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser
    //$dompdf->stream();
    $dompdf->stream();
    $pdf = $dompdf->output();
    file_put_contents("menu.pdf", $pdf);
    //sendEmailTest("email", "bruh", $mail);
    sendEmail("email", "menu.pdf", $mail);
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
    <form method='post'>
        <?php
        var_dump($_POST);
        createMenu($conn); ?>
        <button type='submit'>Submit</button>
    </form>
</main>
</body>
</html>
