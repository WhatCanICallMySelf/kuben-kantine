<?php
require_once "../db_connection.php";
$conn = GetDbConnection();

if (isset($_POST["prep"])) {
    $sql = "SELECT * FROM meny WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_POST["id"]);
    $stmt->execute();
    $select_row = $stmt->get_result();
    $select_res = $select_row->fetch_assoc();
}

$main_sql = "SELECT * FROM meny;";
$main_result = $conn->query($main_sql);

$sql = "SELECT * FROM kategori;";
$result = $conn->query($sql);
$res = $conn->query($sql);
$extra_res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Test - Admin</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<form method="post" action="add.php" class="operation">
    <h3>Legg til</h3>
    <label for="navn">Navn:</label>
    <input type="text" name="navn" id="navn" placeholder="Navn" required>
    <label for="pris">Pris:</label>
    <input type="number" name="pris" id="pris" required placeholder="Pris" min="0" onkeyup="if(value<0) value=null">
    <label for="kategori">Kategori:</label>
    <select name="kategori" id="kategori">
    <?php
    foreach ($res as $value) {
        echo "<option value='".$value["id"]."'>".$value["navn"]."</option>";
    }
    ?>
    </select>
    <button type="submit" name="add">Legg til</button>
</form>

<?php
if ($main_result->num_rows > 0) {
    ?>
    <table>
        <tr>
            <th colspan="6">Meny</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Navn</th>
            <th>Pris</th>
            <th>Kategori</th>
        </tr>
    <?php
    while ($row = $main_result->fetch_assoc()) {
        echo "<tr>";
        foreach($row as $column => $value) {
            echo "<td>$value</td>";
        }
        echo "<td><form method='post' action='delete.php'>";
        echo "<input type='hidden' name='id' value='".$row["id"]."'>";
        echo "<button type='submit' name='delete'>Delete</button>";
        echo "</form></td>";
        echo "<td><form method='post' action=''>";
        echo "<input type='hidden' name='id' value='".$row["id"]."'>";
        echo "<button type='submit' name='prep'>Update</button>";
        echo "</form></td>";
        echo "</tr>";
    }
    ?>
    </table>
    <?php
} else {
    echo "<p>no</p>";
}

if ($extra_res->num_rows > 0) {
    ?>
    <table>
        <tr>
            <th colspan="2">Kategori</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Navn</th>
        </tr>
        <?php
        while ($row = $extra_res->fetch_assoc()) {
            echo "<tr>";
            foreach($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
<?php
}

if (isset($_POST["prep"]) and isset($select_res)) {
    echo "<form action='update.php' method='post' class='operation'>";
    foreach ($select_res as $column => $element) {
        if ($column === "kategori") {
            echo "<select name='kategori'>";
            foreach ($result as $value) {
                echo "<option value='".$value["id"]."'>".$value["navn"]."</option>";
            }
            echo "</select>";
        } elseif ($column === "id") {
            echo "<input type='hidden' name='id' value='$element'>";
            echo "<h4>ID: $element</h4>";
        } else {
            echo "<textarea name='$column'>$element</textarea>";
        }
    }
    echo "<input type='submit' name='update' value='Update'>";
    echo "</form>";
}
?>

</body>
</html>
