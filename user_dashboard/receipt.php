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

$transaction_id = isset($_GET['transaction_id']) ? intval($_GET['transaction_id']) : 0;

if ($transaction_id <= 0) {
    echo "Invalid transaction ID.";
    exit();
}

$user_data = [];

// Fetch transaction data
$sql = "SELECT full_name, phone, email, package, totalCost, amount_paid, payment_date, sign_picture FROM transactions WHERE id=? AND email=?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "is", $transaction_id, $_SESSION['user_email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "Transaction not found or you do not have permission to view this transaction.";
    exit();
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        #receipt {
            padding: 30px;
            margin: 30px;
            background-color: #acf1d5;
        }
        #receipt>img {
            width: 150px;
            float: right;
        }
        table {
            width: 100%;
        }
        table, tr, th, td {
            border: 1px solid gray;
            border-collapse: collapse;
            padding: 10px;
        }
        .flex {
            display: flex;
            flex-direction: row;
        }
        .flex>div {
            width: 50%;
            margin: 20px;
        }
        button {
            border: none;
            border-radius: 10px;
            background-color: #2CB67D;
            padding: 10px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        @media only screen and (max-width: 600px) {
            body {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div id="receipt">
        <img src="../images/logo.png" alt="">
        <div>
            <h1>Receipt</h1>
            <p>+2347042715386, +2348069902316</p>
            <p>INFO@GUDFAMA.COM</p>
            <hr>
            <p>24 Apamini Street Mgbuakara Off Y Junction, Miniorlu Ada-George.Port Harcourt, Rivers state</p>
            <p>Received with thanks from</p>
            <p>Name: <?php echo htmlspecialchars($user_data['full_name']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($user_data['phone']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
            <p>Payment Date: <?php echo htmlspecialchars($user_data['payment_date']); ?></p>
        </div>
        <div>
            <table>
                <tr>
                    <th>PACKAGE</th>
                    <th>PRICE</th>
                    <th>AMOUNT PAID</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($user_data['package']); ?></td>
                    <td><?php echo htmlspecialchars($user_data['totalCost']); ?></td>
                    <td><?php echo htmlspecialchars($user_data['amount_paid']); ?></td>
                </tr>
            </table>
        </div>
        <div >
            
            </div>
            <div><p style="text-align:center">THANK YOU FOR YOUR PATRONAGE</p></div>
        </div>
        <p style="text-align: center"><b>Powered by: Business Gladius Africa and Gladius Urban Farm</b></p>
    </div>
    <div style="display: flex; align-items: center; justify-content: center">
        <button onclick="downloadPDF()">Download Receipt</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const receipt = document.getElementById('receipt');
            const canvas = await html2canvas(receipt);
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF();
            pdf.addImage(imgData, 'PNG', 0, 0);
            pdf.save('receipt.pdf');
        }
    </script>
</body>
</html>
