<?php
session_start();

if (!isset($_SESSION['user_email']) || empty($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$servername = 'sdb-72.hosting.stackcp.net';
$username = "gudfama";
$password = "Nikido886@";
$database = "gudfama-35303631fafd";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_email = $_SESSION['user_email'];
$errorMessage = '';
$successMessage = '';

function fetchSingleResult($connection, $sql, $param_type = null, $param = null) {
    $stmt = mysqli_prepare($connection, $sql);
    if ($param_type && $param) {
        mysqli_stmt_bind_param($stmt, $param_type, $param);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    return $stmt;
}

$user_sql = "SELECT full_name, phone, package, paymentPlan, totalCost, amountPaid, amountToPay FROM users WHERE email = ? LIMIT 1";
$user_stmt = fetchSingleResult($connection, $user_sql, 's', $user_email);
mysqli_stmt_bind_result($user_stmt, $full_name, $phone, $package, $payment_plan, $totalCost, $amountPaid, $amountToPay);
mysqli_stmt_fetch($user_stmt);
mysqli_stmt_close($user_stmt);

$amountLeft = intval($totalCost) - intval($amountPaid);

if ($totalCost == $amountPaid) {
    $successMessage = "Your payment is complete.";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $amount_paid = cleanInput($_POST['amountTopay']);
        $payment_date = cleanInput($_POST['payment_date']);

        if (empty($amount_paid) || empty($payment_date)) {
            $errorMessage = "All fields are required.";
        } elseif (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] != UPLOAD_ERR_OK) {
            $errorMessage = "Receipt upload failed.";
        } else {
            $receipt = $_FILES['receipt'];
            
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($receipt["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($receipt["tmp_name"]);

            if ($check === false) {
                $errorMessage = "File is not an image.";
            } elseif (file_exists($target_file)) {
                $errorMessage = "Sorry, receipt already exists.";
            } elseif ($receipt["size"] > 5000000) {
                $errorMessage = "Sorry, your file is too large.";
            } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                if (move_uploaded_file($receipt["tmp_name"], $target_file)) {
                    $insertReceiptSql = "INSERT INTO receipts (full_name, email, phone, package, payment_plan, amount_paid, receipt, payment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $insertReceiptStmt = mysqli_prepare($connection, $insertReceiptSql);
                    mysqli_stmt_bind_param($insertReceiptStmt, "sssdssss", $full_name, $user_email, $phone, $package, $payment_plan, $amount_paid, $target_file, $payment_date);

                    if (mysqli_stmt_execute($insertReceiptStmt)) {
                        sendWelcomeEmail($user_email, $package, $totalCost, $payment_plan, $amount_paid);
                        $successMessage = "Successfull. Please check your dashboard.";
                    } else {
                        $errorMessage = "Error inserting payment details: " . mysqli_error($connection);
                    }
                    mysqli_stmt_close($insertReceiptStmt);
                } else {
                    $errorMessage = "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendWelcomeEmail($user_email, $package, $totalCost, $payment_plan, $amount_paid) {
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

        $mail->setFrom('info@gudfama.com', 'Gudfama');
        $mail->addAddress($user_email);
        $mail->addReplyTo('info@gudfama.com', 'Gudfama');

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Gudfama';
        $mail->Body = "
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
            <div class='container'>
                <div class='header'>
                    <h1>Recurring Payments</h1>
                </div>
                <div class='content'>
                    <p>Dear esteemed Gudfama,</p>
                    <p>You made a recurring part payment for your farming package on Gudfama, Here are the details of your payment:</p>
                    <p>Package subscribed: $package</p>
                    <p>Total Cost: $totalCost</p>
                    <p>Payment plan: $payment_plan</p>
                    <p>Amount Paid: $amount_paid</p>
                    <p>For now your payment status is unconfirmed, you will receive an email shortly when we confirm your payment.</p>
                    <p>If you need any assistance, our customer care representatives are always online to sort all your needs.</p>
                    <p>Best regards,<br>Gudfama Team</p>
                </div>
                <div class='footer'>
                    <p><span>Powered By:</span> Business Gladius Africa, Gladius Urban Farm Africa and Gudbud</p>
                    <p>+2348069902316 | +2348143507908</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recurring payments</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div id="dashboard" style="display:block">
        <aside>
            <div id="title">
                <h3>Welcome</h3>
                <p><?php echo htmlspecialchars($full_name); ?></p>
            </div>
            <div onclick="openNav()">
                <div class="container" onclick="myFunction(this)" id="sideNav">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
            </div>
        </aside>

        <nav style="z-index: 1;">
            <div id="mySidenav" class="sidenav">
                <a href="dashboard.php">Home</a>
                <a href="account.php">Account</a>
                <a href="invest.php">Invest Now</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
            <script>
                function myFunction(x) {
                    x.classList.toggle("change");
                }
                
                function openNav() {
                    const sideNav = document.getElementById("mySidenav");
                    if (sideNav.style.width === "0px" || sideNav.style.width === "") {
                        sideNav.style.width = "250px";
                    } else {
                        sideNav.style.width = "0";
                    }
                }
            </script>
        </nav>

        <div class="account">
            <div>
                <p><b>Recurring payments (daily, Weekly, Monthly)</b></p>
                <p>Package: <?php echo htmlspecialchars($package); ?></p>
                <p>Total cost: <?php echo htmlspecialchars($totalCost); ?></p>
                <p>Payment plan: <?php echo htmlspecialchars($payment_plan); ?></p>
                <p>Total amount paid: <?php echo htmlspecialchars($amountPaid); ?></p>
                <p>Amount Left to pay: <?php echo htmlspecialchars($amountLeft); ?></p>
                <p>Amount to pay now: <?php echo htmlspecialchars($amountToPay); ?></p>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="number" name="amountTopay" value="<?php echo htmlspecialchars($amountToPay); ?>" hidden>
                    <label >Upload payment receipt: </label><br>
                    <input type="file" name="receipt" id="" style="width:auto"><br>
                    <label for="">When did you pay: </label><br>
                    <input type="date" name="payment_date" style="width:auto"><br>
                    <button type="submit" id="subm" >Submit</button>
                    <?php
if (!empty($errorMessage)) {
    echo "<p style='color:red'>$errorMessage</p>";
} elseif (!empty($successMessage)) {
    echo "<p style='color:green'>$successMessage</p>";
}
?>
                </form>
            </div>
        </div>

        <div id="downNav">
            <a href="dashboard.php">
                <i class="fa-solid fa-house-chimney"></i>
                <p>Homepage</p>
            </a>
            <a href="account.php">
                <i class="fa-solid fa-file-invoice"></i>
                <p>Account</p>
            </a>
            <a id="mid" href="invest.php" style="color:white">
                <i class="fa-solid fa-sack-dollar"></i>
                <p> Farm </p>
            </a>
            <a href="profile.php">
                <i class="fa-solid fa-user"></i>
                <p>Profile</p>  
            </a>
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                <p>Logout</p>
            </a>
        </div>
    </div>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/66584e7a9a809f19fb36e559/1hv4f578f';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
