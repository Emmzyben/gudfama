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

$sql = "SELECT * FROM transactions ORDER BY payment_date DESC";

// Execute the query
$result = mysqli_query($connection, $sql);

// Check if there are results
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}


$errorMessage = '';
$successMessage = '';
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $package = cleanInput($_POST['package']);
    $totalCost = cleanInput($_POST['totalCost']);
    $payment_plan = cleanInput($_POST['payment_plan']);
    $partPayment = cleanInput($_POST['partPayment']);

    // Validate input
    if (empty($package) || empty($totalCost) || empty($payment_plan) || empty($partPayment)) {
        $errorMessage = "All fields are required.";
    } else {
        // Get current amountPaid
        $getAmountPaidSql = "SELECT amountPaid FROM users WHERE email=?";
        $getAmountPaidStmt = mysqli_prepare($connection, $getAmountPaidSql);
        mysqli_stmt_bind_param($getAmountPaidStmt, "i", $user_email);
        mysqli_stmt_execute($getAmountPaidStmt);
        mysqli_stmt_bind_result($getAmountPaidStmt, $currentAmountPaid);
        mysqli_stmt_fetch($getAmountPaidStmt);
        mysqli_stmt_close($getAmountPaidStmt);

       
        $newAmountPaid = $currentAmountPaid + $partPayment;
        $amountToPay = $partPayment;

       
        $_SESSION['package'] = $package;
        $_SESSION['totalCost'] = $totalCost;
        $_SESSION['payment_plan'] = $payment_plan;
        $_SESSION['amountToPay'] = $amountToPay;
        $_SESSION['newAmountPaid'] = $newAmountPaid;

        
        echo "<script>window.location.href = 'payment.php';</script>";
        exit();
    }
}

function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invest</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <link rel="stylesheet" href="profile.css"> 
</head>
<body>
   

<div id="dashboard" style="display:block">
    
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
    <div class="refer">

<p><b>How to start farming</b></p>
<hr>

<p>1. Select the package you want to subscribe to.</p>
<p style="color:rgb(233, 22, 90)">2. Make payment either online or manually</p>
<p>3. After making payment upload your payment receipt and wait for confirmation </p>
<p>4. After confirmation of your payment,you will be provided with a receipt which you can download</p>
<p>5. You will be provided with a contract</p>
<p>6. Monitor your investment on this page</p>

</div>
    <div class="refer">
<h4><i class="fa-solid fa-fire" style="padding-right: 6px;color: red;"></i>Start your farming journey</h4>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
  <div class="form" >
  <div>
  <select name="package" id="package">
    <option value="">Select a package</option>
    <option value="10 Fishes">10 Fishes</option>
    <option value="25 Fishes">25 Fishes</option>
    <option value="50 Fishes">50 Fishes</option>
    <option value="100 Fishes">100 Fishes</option>
    <option value="200 Fishes">200 Fishes</option>
    <option value="500 Fishes">500 Fishes</option>
    <option value="1,000 Fishes">1,000 Fishes</option>
    <option value="2,000 Fishes">2,000 Fishes</option>
    <option value="5,000 Fishes">5,000 Fishes</option>
    <option value="10,000 Fishes">10,000 Fishes</option>
    <option value="20,000 Fishes">20,000 Fishes</option>
  </select>
</div>
<div>
  <label id="label">Total Cost</label>:
  <input type="text" name="totalCost" id="totalCost" value="">
  <span id="error" style="color: red; display: none;">Please choose a package</span>
</div>



<div style="border-bottom-left-radius: 10px;">
  <select name="payment_plan" id="paymentPlan">
    <option value="">Select payment plan</option>
    <option value="full_payment">Full Payment</option>
    <option value="Weekly_Payment">Weekly Payment</option>
    <option value="Monthly_Payment">Monthly Payment</option>
    <option value="Daily_Payment">Daily Payment</option>
  </select><br>
</div>

<div>
  <label id="label">What you will pay</label>:
  <input type="text" name="partPayment" id="partPayment" value="">
</div>

<div>
  <label for="">Upload payment receipt</label>
  <input type="file" name="receipt" id="partPayment" value="">
</div>

</div>
<input type="submit" value="Farm Now" id="subm">
<?php
if (!empty($errorMessage)) {
    echo "<p style='color:red'>$errorMessage</p>";
} elseif (!empty($successMessage)) {
    echo "<p style='color:green'>$successMessage</p>";
}
?>
</form>


</div>






<div class="account">


<div>
<h3>Payment Receipts <i class="fa-solid fa-caret-down"></i></h3>
<p>click on any to preview and download</p>
<ul >
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <li >
                <div style="border-bottom:1px solid gray">
                <p><span>Receipt   <?php echo $row['id']; ?></span> <a style="color:white;background-color:#2CB67D;padding:5px;text-decoration:none" target="blank" href="receipt.php?transaction_id=<?php echo $row['id']; ?>">Preview and download</a></p>
            </div>
        </li>
    <?php endwhile; ?>
</ul>
</div>

<div>
<h3>Contracts <i class="fa-solid fa-caret-down"></i></h3>
<p>Your package contracts will appear here, click on any to download</p>
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

<script src="dashboard.js"></script>
</body>
</html>