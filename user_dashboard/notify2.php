<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $user_email = mysqli_real_escape_string($connection, $_POST['email']);
    $amountToPay = mysqli_real_escape_string($connection, $_POST['amountToPay']);
    $newAmountPaid = mysqli_real_escape_string($connection, $_POST['newAmountPaid']);
    $package = mysqli_real_escape_string($connection, $_POST['package']);
    $totalCost = mysqli_real_escape_string($connection, $_POST['totalCost']);
    $payment_plan = mysqli_real_escape_string($connection, $_POST['payment_plan']);

    // Update user details
    $updateSql = "UPDATE users SET package=?, totalCost=?, paymentPlan=?, amountToPay=?, amountPaid=? WHERE email=?";
    $updateStmt = mysqli_prepare($connection, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "sisiis", $package, $totalCost, $payment_plan, $amountToPay, $newAmountPaid, $user_email);

    if (mysqli_stmt_execute($updateStmt)) {
        echo "Payment and update successful";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }

    
    $full_name = $_SESSION['full_name'];
    $phone = $_SESSION['phone'];
    $email = $_SESSION['user_email'];
    $user_id =$_SESSION['user_id'];
    $payment_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO transactions (user_id, full_name, phone, email, package, paymentPlan, totalCost, amount_paid, payment_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "issssis",$user_id, $full_name, $phone, $email, $package, $payment_plan, $totalCost, $amountToPay, $payment_date);

    if (mysqli_stmt_execute($stmt)) {
        echo "Transaction recorded successfully";
    } else {
        echo "Error recording transaction: " . mysqli_error($connection);
    }
}

// Close connection
mysqli_close($connection);

