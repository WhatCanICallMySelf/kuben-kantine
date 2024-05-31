<?php

use Dompdf\Dompdf;

require 'vendor/autoload.php';
require_once "utils/auth.php";
authRedirect();
function createMenu(mysqli $conn, $input = true): void
{
    $categories = $conn->query("SELECT * FROM kategori")->fetch_all(MYSQLI_ASSOC);
    if ($categories) {
        echo "<h1>MENY FOR KUBENKANTINA</h1>";
        echo "<p>Fyll ut din bestilling og trykk bestill på bunnen av siden</p>";
        echo "<p>For at vi skal klare å levere bestilt mat og drikke, trenger vi litt tid på å få dette inn i logistikken vår. Bestilling må derfor skje senest kl 12.00 dagen før. Dersom bestillingen er uklar, tar vi kontakt med deg. Ved store bestillinger, over 50 personer, må du henvende deg direkte til kjøkkenet for egen avtale. All mat/drikke må hentes på kjøkkenet, hvis annet ikke er avtalt. Dersom du bare skal ha med deg gjester i personalkantina, registrerer du kun antall. Ved spørsmål eller endringer i etterkant, ta kontakt med kantineleder Jens, mob: 40 38 28 39 eller jens.anderberg@osloskolen.no</p>";
        generateLabelAndInput("dato", "date", $input, true);
        generateLabelAndInput("tidspunkt", "text", $input, true);
        echo "<p>Vi ønsker å spise buffet i personalkantina. Skriv inn antall i boksen:</p>";
        generateLabelAndInput("antall", "number", $input, false);
        echo "<p>I tillegg til buffeten ønsker vi kake (kryss av)</p>";
        $checked = isset($_POST["kake"]) ? "checked" : "";
        echo "<input name='kake' id='kake' type='checkbox' value='1' $checked>";
        echo "<p>Hvilket kostnadssted skal belastes?</p>";
        echo "<p>todo ooops?</p>";
        generateLabelAndInput("navn", "string", $input, true);
        generateLabelAndInput("telefonnr", "string", $input, true);
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

    $items = $conn->query("SELECT * FROM meny Where kategori = '$categoryId'")->fetch_all(MYSQLI_ASSOC);
    if ($items) {
        $render = true;
        if (!$input) {
            $render = false;
            foreach ($items as $item) {
                if (!empty($_POST[$item["id"]])) {
                    $render = true;
                }
            }
        }
        if ($render) {
            echo "<table><thead><tr><th colspan='4'>";
            echo "<p>" . ucwords($categoryName) . "</p></th></tr><tr>";
            echo "<th><p>Meny nr.</p></th>";
            echo "<th><p>Meny</p></th>";
            echo "<th><p>Pris</p></th>";
            echo "<th><p>Antall</p></th>";
            echo "</tr></thead><tbody>";
            foreach ($items as $item) {
                if (!$input) {
                    if (!empty($_POST[$item["id"]])) {
                        createItem($item, $input);
                    }
                } else {
                    createItem($item, $input);
                }
            }
            echo "</tbody></table>";
        }
    }
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
        echo "<td><p>$value</p></td>";
    }
    echo "</tr>";
}

function generateLabelAndInput($name, $type, $input, $required): void
{
    $value = $_POST[$name] ?? "";
    if ($input) {
        $required = $required ? "required" : "";
        echo "<label for='$name'>" . ucwords($name) . "</label>";
        echo "<input name='$name' id='$name' type='$type' value='$value' $required>";
    } else {
        echo "<p>" . ucwords($name) . ": $value</p>";
    }
    echo "<br>";
}

include_once "utils/db_connection.php";
$conn = GetDbConnection();
include_once "utils/EmailClient.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate html for conversion to pdf
    $html = '<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"><meta http-equiv="X-UA-Compatible" content="ie=edge"><title>Meny</title><style>';
    $html .= file_get_contents("style.css");
    $html .= '</style></head><body><main>';
    ob_start();
    createMenu($conn, false);
    $html .= ob_get_clean();
    $html .= "</main></body></html>";

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    // Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    // Render the HTML as PDF
    $dompdf->render();
    $dompdf->stream();
    $pdf = $dompdf->output();
    file_put_contents("menu.pdf", $pdf);
    $email = $_SESSION["email"];
    //sendPDFEmail($kantine_email, "menu.pdf", $mail);
    //sendPDFEmail($email, "menu.pdf", $mail);
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
    <form method='post' action="">
        <?php createMenu($conn); ?>
        <br>
        <button type='submit'>Bestill</button>
    </form>
</main>
</body>
</html>
