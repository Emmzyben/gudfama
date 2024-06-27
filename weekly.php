<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";
// Create a database connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Define function to send email
function sendReminderEmail($email, $full_name, $paymentPlan) {
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

        $mail->setFrom('info@gudfama.com', 'Gudfama');
        $mail->addAddress($email, $full_name);
        $mail->addReplyTo('info@gudfama.com', 'Gudfama');

        $mail->isHTML(true);
        $mail->Subject = 'Payment Reminder';
        $mail->Body = '
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
                    <h1>Payment Reminder</h1>
                </div>
                <div class="content">
                    <p>Dear ' . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . ',</p>
                    <p>This is a reminder for your ' . htmlspecialchars($paymentPlan, ENT_QUOTES, 'UTF-8') . ' payment plan with Gudfama. Please make sure to complete your payment on time.</p>
                    <p>Thank you,<br>Gudfama Team</p>
                </div>
                <div class="footer">
                    <p>Powered By: Business Gladius Africa, Gladius Urban Farm Africa, and Gudbud</p>
                    <p>+2348069902316 | +2348143507908</p>
                </div>
            </div>
        </body>
        </html>';

        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }
}

// Fetch users with weekly payment plan
$weeklyQuery = "SELECT full_name, email, totalCost, amountPaid FROM users WHERE paymentPlan = 'Weekly_payment'";
$weeklyResult = mysqli_query($connection, $weeklyQuery);

if ($weeklyResult) {
    while ($row = mysqli_fetch_assoc($weeklyResult)) {
        // Check if totalCost and amountPaid are not equal
        if ($row['totalCost'] != $row['amountPaid']) {
            sendReminderEmail($row['email'], $row['full_name'], 'Weekly');
        }
    }
} else {
    error_log("Query failed: " . mysqli_error($connection));
}

mysqli_close($connection);
?>
