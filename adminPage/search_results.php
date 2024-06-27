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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['name'])) {
    $searchName = $_GET['name'];

    // Sanitize the input
    $searchName = mysqli_real_escape_string($connection, $searchName);

    // Create the SQL query
    $sql = "SELECT * FROM users WHERE full_name LIKE ?";
    $stmt = mysqli_prepare($connection, $sql);
    $searchTerm = "%" . $searchName . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subscribed Before?</th>
                <th>Date of Birth</th>
                <th>State of Residence</th>
                <th>Password</th>
                <th>How Did You Hear About Us</th>
                <th>Registration Date</th>
                <th>Account Name</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>Package</th>
                <th>Payment Plan</th>
                <th>Total Cost</th>
                <th>Amount Paid</th>
                <th>Amount To Pay</th>
                <th>Referral Link</th>
                <th>Receipt</th>
                <th>Payment Date</th>
                <th>Terms accepted</th>
                <th>Confirm payment</th>
                <th>Batch</th>
            </tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['subscription']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                echo "<td>" . htmlspecialchars($row['state_of_residence']) . "</td>";
                echo "<td>" . htmlspecialchars($row['how_did_you_hear_about_us']) . "</td>";
                echo "<td>" . htmlspecialchars($row['registration_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['accountName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bankName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['accountNumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['package']) . "</td>";
                echo "<td>" . htmlspecialchars($row['paymentPlan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['totalCost']) . "</td>";
                echo "<td>" . htmlspecialchars($row['amountPaid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['amountToPay']) . "</td>";
                echo "<td>" . htmlspecialchars($row['referralLink']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row['receipt']) . "' target='_blank'>View Receipt</a></td>";
                echo "<td>" . htmlspecialchars($row['payment_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['terms_accepted']) . "</td>";
                echo "<td>" . htmlspecialchars($row['confirmation']) . "</td>";
                echo "<td>" . htmlspecialchars($row['batch']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<a href='accountsPage.php' >Close</a>";
        } else {
            echo "No results found for the name: " . htmlspecialchars($searchName);
        }
    } else {
        echo "Error retrieving data: " . mysqli_error($connection);
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($connection);
