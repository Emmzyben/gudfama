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

$errorMessage = '';
$successMessage ='';
$accountName = '';
$bankName = '';
$accountNumber = '';

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

try {
    // Fetch transactions
    $sql = "SELECT * FROM transactions WHERE email = ? ORDER BY payment_date DESC";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt === false) {
        throw new Exception("Failed to prepare statement: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    $result_set = mysqli_stmt_get_result($stmt);

    if ($result_set === false) {
        throw new Exception("Query failed: " . mysqli_error($connection));
    }

  $sql = "SELECT * FROM Batches WHERE email = ? ORDER BY payment_date DESC";
    $stmt3 = mysqli_prepare($connection, $sql);

    if ($stmt3 === false) {
        throw new Exception("Failed to prepare statement: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt3, "s", $user_email);
    mysqli_stmt_execute($stmt3);
    $result_set3 = mysqli_stmt_get_result($stmt3);

    if ($result_set3 === false) {
        throw new Exception("Query failed: " . mysqli_error($connection));
    }

    // Fetch contracts
    $sql = "SELECT * FROM contract WHERE email = ? ORDER BY payment_date DESC";
    $stmt2 = mysqli_prepare($connection, $sql);

    if ($stmt2 === false) {
        throw new Exception("Failed to prepare statement: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt2, "s", $user_email);
    mysqli_stmt_execute($stmt2);
    $result_set2 = mysqli_stmt_get_result($stmt2);

    if ($result_set2 === false) {
        throw new Exception("Query failed: " . mysqli_error($connection));
    }

    // Fetch account details
    $sql = "SELECT accountName, bankName, accountNumber FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $accountName, $bankName, $accountNumber);
        mysqli_stmt_fetch($stmt);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accountName = $_POST['accountName'];
        $bankName = $_POST['bankName'];
        $accountNumber = $_POST['accountNumber'];

        if (empty($accountName) || empty($bankName) || empty($accountNumber)) {
            $errorMessage = "All fields are required.";
        } else {
            // Check if account details already submitted
            $checkQuery = "SELECT * FROM users WHERE accountName=? AND bankName=? AND accountNumber=? AND email=?";
            $checkStmt = mysqli_prepare($connection, $checkQuery);
            mysqli_stmt_bind_param($checkStmt, "ssss", $accountName, $bankName, $accountNumber, $user_email);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $errorMessage = "Account already submitted.";
            } else {
                // Check if the user exists in the table
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_prepare($connection, $sql);
                mysqli_stmt_bind_param($stmt, "s", $user_email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    // User exists, update the existing row
                    $updateSql = "UPDATE users SET accountName=?, bankName=?, accountNumber=? WHERE email=?";
                    $updateStmt = mysqli_prepare($connection, $updateSql);
                    mysqli_stmt_bind_param($updateStmt, "ssss", $accountName, $bankName, $accountNumber, $user_email);

                    if (mysqli_stmt_execute($updateStmt)) {
                        $successMessage = "Account details updated successfully!";
                    } else {
                        throw new Exception("Error: " . mysqli_error($connection));
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account manager</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <style>
        .account{
    margin-bottom: 10%;
}
      .account>div{
        margin: 30px;
        background-color: #fff;padding: 10px;border-radius: 20px;
      }
      .account h3{
        color: #2CB67D;
      }
     
      .account form>input{
        padding: 6px;border: 1px solid rgb(221, 218, 218);border-radius: 8px; width: 300px;margin-bottom: 10px;
      }
table,tr,th,td{
    border:1px solid gray;
    border-collapse:collapse;
}
th{
    background-color:#2CB67D;color:white;
}
th,td{
    padding:10px;
}
       @media screen and (max-width: 700px) {
        .account{
            margin-bottom: 30%;
        }
        .account form>input{
             width: 100%;
          }}
    </style>
</head>
<body>
   

<div id="dasherboard" style="display:block">
    
<aside> 
        <div id="title">
          <h3>Welcome</h3>
          <p>Emmanuel</p>
      </div> 
          <div onclick="openNav()" >
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
          <a href="profile.php" >Profile</a>
            <a href="logout.php">Logout</a>
        </div>
        <script>
        
    function myFunction(x) {
        x.classList.toggle("change");
      }
    
      var open = false;
    
    function openNav() {
        var sideNav = document.getElementById("mySidenav");
        
        if (sideNav.style.width === "0px" || sideNav.style.width === "") {
            sideNav.style.width = "250px";
            open = true;
        } else {
            sideNav.style.width = "0";
            open = false;
        }
    }
        </script>
    </nav>

<div class="account">

<div>
<h3>Enter account details</h3>
<p>Please enter your account details (Note: you cannot modify after submitting)</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="">Account Name:</label><br>
    <input type="text" name="accountName"><br>
    <label for="">Bank Name:</label><br>
    <input type="text" name="bankName"><br>
    <label for="">Account Number:</label><br>
    <input type="number" name="accountNumber"><br>
    <input type="submit" name="" id="submit">
    <?php
if (!empty($errorMessage)) {
    echo "<p style='color:red'>$errorMessage</p>";
} elseif (!empty($successMessage)) {
    echo "<p style='color:green'>$successMessage</p>";
}
?>
</form>
</div>

<div>
<h3>Account Details<i class="fa-solid fa-caret-down"></i></h3>
<?php if(!empty($accountName) && !empty($bankName) && !empty($accountNumber)) : ?>
    <p>Account Name: <?php echo $accountName; ?></p>
    <p>Bank Name: <?php echo $bankName; ?></p>
    <p>Account Number: <?php echo $accountNumber; ?></p>
<?php endif; ?>
</div>


<div>
<h3>Payment Receipts <i class="fa-solid fa-caret-down"></i></h3>
<p>click on any to preview and download</p>
<ul>
    <?php while ($row = mysqli_fetch_assoc($result_set)): ?>
        <li>
            <div style="border-bottom:1px solid gray">
                <p><span>Receipt <?php echo $row['id']; ?></span> <a style="color:white;background-color:#2CB67D;padding:5px;text-decoration:none" target="blank" href="receipt.php?transaction_id=<?php echo $row['id']; ?>">Preview and download</a></p>
            </div>
        </li>
    <?php endwhile; ?>
</ul>
</div>

<div>
<h3>Contracts <i class="fa-solid fa-caret-down"></i></h3>
<p>click on any to preview and download</p>
<ul>
    <?php while ($row = mysqli_fetch_assoc($result_set2)): ?>
        <li>
            <div style="border-bottom:1px solid gray">
                <p><span>Contract <?php echo $row['id']; ?></span> <a style="color:white;background-color:#2CB67D;padding:5px;text-decoration:none" target="blank" href="contract.php?contract_id=<?php echo $row['id']; ?>">Preview and download</a></p>
            </div>
        </li>
    <?php endwhile; ?>
</ul>
</div>

<div>
<h3>Former Batches <i class="fa-solid fa-caret-down"></i></h3>
<div  style="overflow-x: auto;">
    <table>
    <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Package</th>
        <th>Total Cost</th>
        <th>Amount Paid</th>
        <th>Payment Date</th>
        <th>Disbursement Date</th>
        <th>Returns</th>
        <th>Batch</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result_set3)): ?>
    <tr>
        <td><?php echo $row['full_name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['package']; ?></td>
        <td><?php echo $row['totalCost']; ?></td>
        <td><?php echo $row['amount_paid']; ?></td>
        <td><?php echo $row['payment_date']; ?></td>
        <td><?php echo $row['disbursement_date']; ?></td>
        <td><?php echo $row['returns']; ?></td>
        <td><?php echo $row['batch']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</div>


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