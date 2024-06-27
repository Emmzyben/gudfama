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

$contract_id = isset($_GET['contract_id']) ? intval($_GET['contract_id']) : 0;

if ($contract_id <= 0) {
    echo "Invalid contract ID.";
    exit();
}

$user_data = [];

// Fetch transaction data
$sql = "SELECT full_name, address, email, package, totalCost, amount_paid, payment_date, returns FROM contract WHERE id=? AND email=?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "is", $contract_id, $_SESSION['user_email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "contract not found or you do not have permission to view this transaction.";
    exit();
}

mysqli_close($connection);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract</title>
    <style>
        body{
            padding: 30px;
            font-family:'Times New Roman', Times, serif;
            font-size: 14px;
        }
    </style>
</head>
<body id="contract">
<div style="text-align:center">
<h2 style="color:#2CB67D">GUDFAMA ASSISTANCE FARMING AGREEMENT</h2>
    <p>THIS AGREEMENT is made this      day of  <b><?php echo htmlspecialchars($user_data['payment_date']); ?></b> </p>
<p>BETWEEN</p>
<p><b style="color:#2CB67D">GLADIUS URBAN FARM AFRICA</b> a business name duly registered under the Companies and Allied Matters Act 2020 with its registered office located at No.13 Rumuodaolu Market Road, Off Rumuola Road. Port harcourt, Rivers-State. (Herein referred to as Gladius) of the one part. 
</p> 
<p>AND</p>
<p>Mr/Mrs/Miss  <b><?php echo htmlspecialchars($user_data['full_name']); ?></b>   of  <b><?php echo htmlspecialchars($user_data['address']); ?></b>  (Herein referred to as Gudfama), of the other part.</p> 

</div><p>NOW THIS DEED WITNESSES as follows:</p> 
<p>1.1 Gladius Urban Farm Africa is into fishing, Aquaculture and sale of agriculture produce. Gladius Urban Farm Africa provides an avenue to sensitize and educate individuals about fish farming while also providing a platform to farm virtually anywhere in the world through our Gudfama Assistance program. </p>
<p>1.2 The Gudfama is desirous of engaging in the farming assistance program provided by the Gladius upon the terms and conditions contained herein.</p>
<p>2.1 Gudfama has shown interest in rearing <b><?php echo htmlspecialchars($user_data['package']); ?></b> for a period of four months starting  from  <?php echo htmlspecialchars($user_data['payment_date']); ?>  to           2024.</p>
<p>2.2 Gudfama has agreed to acquire <b><?php echo htmlspecialchars($user_data['package']); ?></b>   ( juvenile) at the rate of ₦78 per Juvenile which will amount to <b><?php echo htmlspecialchars($user_data['totalCost']); ?></b></p>          
<p>2.3 Gudfama requests for the assistance of Gladius in rearing/training of  <b><?php echo htmlspecialchars($user_data['package']); ?></b> for a period of four months at ₦ 11 per day/ per fish which amounts to <b> <?php echo htmlspecialchars($user_data['totalCost']); ?> </b> for the entire four months duration (this will cover maintenance, operation, treatment, feeding, logistics etc.)</p>
<p>2.4 Gudfama has made a complete payment of <b> <?php echo htmlspecialchars($user_data['totalCost']); ?></b>  for rearing/training <?php echo htmlspecialchars($user_data['package']); ?> fishes for a period of 4 months.</p>
<p>2.5 If Gudfama fails to complete their full payment before the due date as stipulated in this agreement, their return on investment will be transferred to the subsequent batch.</p>
<p>2.6 This agreement shall be valid for a period of four (4) months.</p>
<p>2.7 Gladius covenants to bear the loss that may arise either from mortality or otherwise during the four (4) months investment period.</p>
<p>2.8 At the end of the four(4) months, the Gudfama can take his/her fishes or authorise Gladius to sell on his/her behalf at an agreed rate of ₦1,900 (One thousand nine hundred naira) per fish which will amount to <b><?php echo htmlspecialchars($user_data['returns']); ?></b></p>          
<p>2.9 Failure by either party to fulfill their contractual obligations in a manner that obstructs the intended purpose of this agreement/contract may render the contract voidable contingent upon the actions of the other party.</p>

<p>I <b><?php echo htmlspecialchars($user_data['full_name']); ?></b> have agreed to the above terms and conditions</p>

<div style="display: flex; align-items: center; justify-content: center">
        <button onclick="printReceipt()" style="background-color:#2CB67D;color:white;padding:10px;border:none">Print Contract</button>
    </div>
    <script>
        function printReceipt() {
            const printButton = document.querySelector('button');
            printButton.style.display = 'none';
            window.print();
            printButton.style.display = 'block';
        }
    </script>
    
</body>
</html>