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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $confirmation = $_POST['confirmation'];

    // Sanitize inputs
    $email = mysqli_real_escape_string($connection, $email);
    $confirmation = mysqli_real_escape_string($connection, $confirmation);

    // Update the confirmation status in the users table
    $updateSql = "UPDATE users SET confirmation=? WHERE email=?";
    $stmt = mysqli_prepare($connection, $updateSql);
    mysqli_stmt_bind_param($stmt, "ss", $confirmation, $email);

    if (mysqli_stmt_execute($stmt)) {
        echo "Confirmation status updated successfully.";

        // Only insert into the transactions table if the confirmation is "confirmed"
        if ($confirmation === "confirmed") {
            // Fetch details from users table to insert into transactions table
            $selectSql = "SELECT id, full_name, phone, package, paymentPlan, totalCost, amountPaid FROM users WHERE email=?";
            $selectStmt = mysqli_prepare($connection, $selectSql);
            mysqli_stmt_bind_param($selectStmt, "s", $email);
            mysqli_stmt_execute($selectStmt);
            mysqli_stmt_bind_result($selectStmt, $user_id, $full_name, $phone, $package, $payment_plan, $totalCost, $amountPaid);
            mysqli_stmt_fetch($selectStmt);
            mysqli_stmt_close($selectStmt);

            // Insert details into transactions table
            $insertSql = "INSERT INTO transactions (user_id, full_name, phone, email, package, paymentPlan, totalCost, amount_paid, payment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($connection, $insertSql);
            $payment_date = date('Y-m-d'); // Assuming you want to use the current date as payment date
            mysqli_stmt_bind_param($insertStmt, "isssssdss", $user_id, $full_name, $phone, $email, $package, $payment_plan, $totalCost, $amountPaid, $payment_date);

            if (mysqli_stmt_execute($insertStmt)) {
                sendWelcomeEmail($email, $full_name, $package, $amountPaid);
                echo "Transaction recorded successfully.";
            } else {
                echo "Error recording transaction: " . mysqli_error($connection);
            }
            mysqli_stmt_close($insertStmt);
        }
    } else {
        echo "Error updating confirmation status: " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
}

function sendWelcomeEmail($email, $full_name, $package, $amountPaid) {
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;  
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';  
        $mail->SMTPAuth = true;
        $mail->Username = '7616e4002@smtp-brevo.com';
        $mail->Password = '1qzVSdU0I5KsQ24p';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('info@gudfama.com', 'Gudfama');
        $mail->addAddress($email);
        $mail->addReplyTo('info@gudfama.com', 'Gudfama');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Gudfama';
        $mail->Body    = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #fff;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background-color: #2CB67D;
                    color: white;
                    padding: 10px 0;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                }
                .footer {
                    background-color: #2CB67D;
                    color: white;
                    text-align: center;
                    padding: 10px 0;
                    margin-top: 20px;
                }
                .footer>p>span{
                    background-color:white;color:black;padding:6px;
                    }
                h1 {
                    color: #333333;
                }
                p {
                    line-height: 1.6;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Payment Confirmation</h1>
                </div>
                <div class="content">
                    <p>Dear '.$full_name.',</p>
                    <p>Your payment has been confirmed, here is a summary:</p>
                    <p>Package subscribed:'. $package .'</p>
                       <p>Amount Paid:'.  $amountPaid .'</p>
                    <p>Your payment status has been changed to confirmed and a payment receipt has been generated automatically in your dashboard. log into your dashboard to confirm</p>
                    <p>If you need any assistance, our customer care representatives are always online to sort all your needs.</p>
                    <p>Best regards,<br>Gudfama Team</p>
                </div>
                <div class="footer">
                    <p><span>Powered By:</span> Business Gladius Africa, Gladius Urban Farm Africa and Gudbud</p>
                <p> +2348069902316 | +2348143507908</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }
}



mysqli_close($connection);



