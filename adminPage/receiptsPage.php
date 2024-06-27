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

$email = isset($_GET['email']) ? $_GET['email'] : '';

if (empty($email)) {
    echo "Invalid email.";
    exit();
}

// Fetch all receipts for the user
$sql = "SELECT id, full_name, email, phone, amount_paid, receipt, confirmation, payment_date FROM receipts WHERE email=?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Amount Paid</th>
                <th>Receipt</th>
                <th>Confirmation</th>
                <th>Payment Date</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['full_name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['amount_paid']) . "</td>
                <td><a href='" . htmlspecialchars($row['receipt']) . "' target='_blank'>View Receipt</a></td>
                <td>
                    <select name='confirmation[{$row['id']}]' onchange='updateConfirmation(\"{$row['id']}\", \"{$email}\", this)'>
                        <option value='unconfirmed'" . ($row['confirmation'] == 'unconfirmed' ? ' selected' : '') . ">Unconfirmed</option>
                        <option value='confirmed'" . ($row['confirmation'] == 'confirmed' ? ' selected' : '') . ">Confirmed</option>
                        <option value='unsuccessful'" . ($row['confirmation'] == 'unsuccessful' ? ' selected' : '') . ">Unsuccessful</option>
                    </select>
                </td>
                <td>" . htmlspecialchars($row['payment_date']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No transactions found for this email.";
}

mysqli_close($connection);
?>

<script>
function updateConfirmation(id, email, selectElement) {
    var confirmationStatus = selectElement.value;

    // Make an AJAX request to update the confirmation status in the database
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_confirmation2.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                alert("Confirmation status updated successfully.");
            } else {
                alert("Error updating confirmation status: " + xhr.responseText);
            }
        }
    };
    xhr.send("id=" + encodeURIComponent(id) + "&email=" + encodeURIComponent(email) + "&confirmation=" + encodeURIComponent(confirmationStatus));
}
</script>
