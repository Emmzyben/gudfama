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

$transaction_id = isset($_POST['id']) ? $_POST['id'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$confirmation = isset($_POST['confirmation']) ? $_POST['confirmation'] : '';

if (empty($transaction_id) || empty($email) || empty($confirmation)) {
    echo "Invalid data.";
    exit();
}

// Update confirmation status in receipts table
$updateSql = "UPDATE receipts SET confirmation=? WHERE id=? AND email=?";
$updateStmt = mysqli_prepare($connection, $updateSql);
mysqli_stmt_bind_param($updateStmt, "sis", $confirmation, $transaction_id, $email);

if (mysqli_stmt_execute($updateStmt)) {
    if ($confirmation === 'confirmed') {
        // Fetch amount_paid and other details from receipts table for the given transaction_id
        $sql_receipt = "SELECT full_name, phone, amount_paid FROM receipts WHERE id=? AND email=?";
        $stmt_receipt = mysqli_prepare($connection, $sql_receipt);
        mysqli_stmt_bind_param($stmt_receipt, "is", $transaction_id, $email);
        mysqli_stmt_execute($stmt_receipt);
        $result_receipt = mysqli_stmt_get_result($stmt_receipt);

        if ($result_receipt && mysqli_num_rows($result_receipt) > 0) {
            $row_receipt = mysqli_fetch_assoc($result_receipt);
            $full_name = $row_receipt['full_name'];
            $phone = $row_receipt['phone'];
            $amount_paid_receipt = $row_receipt['amount_paid'];

            // Fetch amountPaid from users table for the given email
            $sql_user = "SELECT amountPaid FROM users WHERE email=?";
            $stmt_user = mysqli_prepare($connection, $sql_user);
            mysqli_stmt_bind_param($stmt_user, "s", $email);
            mysqli_stmt_execute($stmt_user);
            $result_user = mysqli_stmt_get_result($stmt_user);

            if ($result_user && mysqli_num_rows($result_user) > 0) {
                $row_user = mysqli_fetch_assoc($result_user);
                $amountPaid_user = $row_user['amountPaid'];

                // Calculate new amountPaid
                $new_amountPaid = $amountPaid_user + $amount_paid_receipt;

                // Update users table with the new amountPaid
                $updateUserSql = "UPDATE users SET amountPaid=? WHERE email=?";
                $updateUserStmt = mysqli_prepare($connection, $updateUserSql);
                mysqli_stmt_bind_param($updateUserStmt, "ds", $new_amountPaid, $email);

                if (mysqli_stmt_execute($updateUserStmt)) {
                    // Insert into transactions table
                    $payment_date = date('Y-m-d'); // Assuming you want to use the current date as payment date
                    $insertSql = "INSERT INTO transactions (full_name, phone, email, amount_paid, payment_date) VALUES (?, ?, ?, ?, ?)";
                    $insertStmt = mysqli_prepare($connection, $insertSql);
                    mysqli_stmt_bind_param($insertStmt, "ssds", $full_name, $phone, $email, $amount_paid_receipt, $payment_date);

                    if (mysqli_stmt_execute($insertStmt)) {
                        sendWelcomeEmail($email, $full_name, $amount_paid_receipt);
                        echo "Transaction recorded successfully.";
                    } else {
                        echo "Error recording transaction: " . mysqli_error($connection);
                    }
                    mysqli_stmt_close($insertStmt);
                } else {
                    echo "Error updating users table: " . mysqli_error($connection);
                }
                mysqli_stmt_close($updateUserStmt);
            } else {
                echo "Error: User not found for the specified email.";
            }
        } else {
            echo "Error: Receipt not found for the specified transaction.";
        }
    } else {
        echo "Confirmation status updated successfully but not confirmed.";
    }
} else {
    echo "Error updating confirmation status: " . mysqli_error($connection);
}

function sendWelcomeEmail($email, $full_name, $amount_paid_receipt) {
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
                    <h1>Recurring Payments confirmation</h1>
                </div>
                <div class="content">
                    <p>Dear '.$full_name.',</p>
                    <p>Your part payment of '. $amount_paid_receipt .' has been confirmed</p>
                    <p>A payment receipt has been generated automatically in your dashboard. log into your dashboard to confirm</p>
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

mysqli_stmt_close($updateStmt);
mysqli_close($connection);
