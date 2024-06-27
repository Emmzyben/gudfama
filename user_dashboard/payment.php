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

$package = $_SESSION['package'];
$totalCost = $_SESSION['totalCost'];
$payment_plan = $_SESSION['payment_plan'];
$amount2Pay = $_SESSION['amountToPay'];
$newAmountPaid = $_SESSION['newAmountPaid'];

$user_email = $_SESSION['user_email']; 
$amountToPay = $amount2Pay;

$sql = "SELECT full_name, phone FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($connection, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $full_name, $phone);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    die("Failed to prepare the statement for user details: " . mysqli_error($connection));
}

$_SESSION['full_name'] = $full_name;
$_SESSION['phone'] = $phone;

$sql = "SELECT public_key FROM publicKey LIMIT 1";
$stmt = mysqli_prepare($connection, $sql);
if ($stmt) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $public_key);
        mysqli_stmt_fetch($stmt);
    } else {
        echo "No public key found.";
    }
    mysqli_stmt_close($stmt);
} else {
    die("Failed to prepare the statement for public key: " . mysqli_error($connection));
}

mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing payment</title>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        var publicKey =<?php echo json_encode($public_key); ?>; 
        var transactionRef = "txn_" + new Date().getTime(); 
        var amountToPay = <?php echo json_encode($amountToPay); ?>;
        var customerEmail = <?php echo json_encode($_SESSION['user_email']); ?>;
        var phoneNumber = <?php echo json_encode($phone); ?>;
        var customerName = <?php echo json_encode($full_name); ?>;

        function makePayment() {
    FlutterwaveCheckout({
        public_key: publicKey,
        tx_ref: transactionRef,
        amount: amountToPay,
        currency: "NGN",
        payment_options: "card, banktransfer, ussd",
        meta: {
            source: "docs-inline-test",
            consumer_mac: "92a3-912ba-1192a",
        },
        customer: {
            email: customerEmail,
            phone_number: phoneNumber,
            name: customerName,
        },
        customizations: {
            title: "Gudfama",
            description: "Invest in a package",
            logo: "https://checkout.flutterwave.com/assets/img/rave-logo.png",
        },
        callback: function(response) {
            if (response.status === "successful") {
                // Call notify.php upon successful payment
                var notifyXhr = new XMLHttpRequest();
                notifyXhr.open("POST", "notify.php", true);
                notifyXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                notifyXhr.onreadystatechange = function() {
                    if (notifyXhr.readyState === 4 && notifyXhr.status === 200) {
                        alert("Payment Successful");
                    }
                };
                notifyXhr.send("email=" + encodeURIComponent(customerEmail) + "&amountToPay=" + encodeURIComponent(amountToPay) + "&newAmountPaid=" + encodeURIComponent(<?php echo json_encode($newAmountPaid); ?>) + "&package=" + encodeURIComponent(<?php echo json_encode($package); ?>) + "&totalCost=" + encodeURIComponent(<?php echo json_encode($totalCost); ?>) + "&payment_plan=" + encodeURIComponent(<?php echo json_encode($payment_plan); ?>));
            } else {
                alert("payment failed")
                window.location.href = "invest.php";
            }
        },
        onclose: function(incomplete) {
            if (incomplete === true) {
                alert("payment incomplete")
                window.location.href = "invest.php";
            }
            // Handle other cases if needed
        }
    });
}


    </script>
</head>
<body onload="makePayment()">
    <h2>Processing Payment...</h2>
</body>
</html>
