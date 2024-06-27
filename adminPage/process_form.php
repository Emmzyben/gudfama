<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: adminlogin.php');
    exit;
}

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fields'])) {
    $fields = $_POST['fields'];

    // Validate the fields array
    if (empty($fields)) {
        die("No fields selected.");
    }

    // Sanitize and prepare the fields for SQL query
    $selectedFields = implode(", ", array_map('mysqli_real_escape_string', array_fill(0, count($fields), $connection), $fields));

    // Create the SQL query
    $sql = "SELECT $selectedFields FROM users";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo "<table border='1'>";
        echo "<tr style='background-color:#2CB67D;color:white'>";
        foreach ($fields as $field) {
            echo "<th>" . htmlspecialchars($field) . "</th>";
        }
        echo "</tr>";

        // Fetch and display the results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<td>" . htmlspecialchars($row[$field]) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
echo "<a href='accountsPage.php' >Close</a>";
        mysqli_free_result($result);
    } else {
        echo "Error retrieving data: " . mysqli_error($connection);
    }
} else {
    echo "Invalid form submission.";
}

mysqli_close($connection);

