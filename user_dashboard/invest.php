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
$successMessage = '';
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

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



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $package = cleanInput($_POST['package']);
    $totalCost = cleanInput($_POST['totalCost']);
    $totalreturn = cleanInput($_POST['totalreturn']);
    $payment_plan = cleanInput($_POST['payment_plan']);
    $partPayment = cleanInput($_POST['partPayment']);
    $payment_date = cleanInput($_POST['payment_date']);
    $batch = cleanInput($_POST['batch']);
    $terms = isset($_POST['terms']) && $_POST['terms'] == 'accepted';
  

    if (empty($package) || empty($totalCost) || empty($totalreturn) || empty($payment_plan) || empty($partPayment) || empty($payment_date) || empty($batch) || !$terms) {
        $errorMessage = "All fields are required, and terms and conditions must be accepted.";
    } elseif (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] != UPLOAD_ERR_OK) {
        $errorMessage = "Receipt upload failed.";
    } else {
        $receipt = $_FILES['receipt'];
        
        // Handle image upload
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
        } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($receipt["tmp_name"], $target_file)) {
                $getAmountPaidSql = "SELECT amountPaid FROM users WHERE email=?";
                $getAmountPaidStmt = mysqli_prepare($connection, $getAmountPaidSql);
                mysqli_stmt_bind_param($getAmountPaidStmt, "s", $user_email);
                mysqli_stmt_execute($getAmountPaidStmt);
                mysqli_stmt_bind_result($getAmountPaidStmt, $currentAmountPaid);
                mysqli_stmt_fetch($getAmountPaidStmt);
                mysqli_stmt_close($getAmountPaidStmt);

                $newAmountPaid = $currentAmountPaid + $partPayment;

                $updateUserSql = "UPDATE users SET package=?, totalCost=?, totalreturn=?, paymentPlan=?, amountPaid=?, amountTopay=?, receipt=?, terms_accepted=?, payment_date=?, batch=? WHERE email=?";
                $updateUserStmt = mysqli_prepare($connection, $updateUserSql);
                $termsAccepted = 1; 
                mysqli_stmt_bind_param($updateUserStmt, "ssssdssssss", $package, $totalCost, $totalreturn, $payment_plan, $newAmountPaid, $partPayment, $target_file, $termsAccepted, $payment_date, $batch, $user_email);

                if (mysqli_stmt_execute($updateUserStmt)) {
                  sendWelcomeEmail($user_email, $package, $totalCost, $totalreturn, $payment_plan, $partPayment, $batch);
                    $successMessage = "You have been subscribed successfully. Please check your dashboard for confirmation status.";
                } else {
                    $errorMessage = "Error updating payment details: " . mysqli_error($connection);
                }
                mysqli_stmt_close($updateUserStmt);
            } else {
                $errorMessage = "Sorry, there was an error uploading your file.";
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

function sendWelcomeEmail($user_email, $package, $totalCost, $totalreturn, $payment_plan, $partPayment, $batch) {
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
        $mail->addAddress($user_email);
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
                    <h1>Congratulations!</h1>
                </div>
                <div class="content">
                    <p>Dear esteemed Gudfama,</p>
                    <p>You have subscribed for a farming package on Gudfama for the '. $batch .' batch, Here are the details of your subscription</p>
                    <p>Package subscribed:'. $package .'</p>
                    <p>Total Cost:'.  $totalCost .'</p>
                     <p>Total Return:'. $totalreturn .'</p>
                     <p>Payment plan:'. $payment_plan .'</p>
                       <p>Amount Paid:'.  $partPayment .'</p>
                    <p>For now your payment status is uncomfirmed, you will receive an email shortly when we confirm your payment</p>
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
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invest</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <link rel="stylesheet" href="profile.css"> 
    <style>
      @media screen and (max-width: 700px) {
        .form{
        display: flex;flex-direction: column;margin-top: 10px;
       }
       .form input{
           padding: 8px;border: 1px solid rgb(196, 189, 189);
       }
.form>div{
  width: 90%;display: flex;flex-direction: column;margin: 10px;
}
      }
    </style>
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
<p style="color:rgb(233, 22, 90)">2. Make payment to the account below:</p>
<p>
  <ul>
<li>Account name: Business Gladius Africa</li> 
<li>Account number: 0084987357</li> 
<li>Bank: Sterling Bank</li>
  </ul>
</p>
<p>3. After making payment upload your payment receipt and wait for confirmation</p>
<p>4. After confirmation of your payment,you will be provided with a receipt</p>
<p><span style="color:red">Please Note:</span> A contract will only be provided after you make full payment</p>
</div>


    <div class="refer">
<h4><i class="fa-solid fa-fire" style="padding-right: 6px;color: red;"></i>Start your farming journey</h4>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
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
  <label id="label">Total Cost</label>
  <input type="text" name="totalCost" id="totalCost" value="">
  <span id="error" style="color: red; display: none;">Please choose a package</span>
</div>

<div>
  <label id="label">Expected return</label>
  <input type="text" name="totalreturn" id="returns" value="">
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
  <label id="label">What you will pay</label>
  <input type="text" name="partPayment" id="partPayment" value="">
</div>

<div style="border-bottom-left-radius: 10px;">
<select name="batch">
  <option value="">Select Batch</option>
    <option value="January">January</option>
    <option value="February">February</option>
    <option value="March">March</option>
    <option value="April">April</option>
    <option value="May">May</option>
    <option value="June">June</option>
    <option value="July">July</option>
    <option value="August">August</option>
    <option value="September">September</option>
    <option value="October">October</option>
    <option value="November">November</option>
    <option value="December">December</option>
</select>

</div>

<div style="border-bottom-left-radius: 10px;">
  <label id="label">Upload payment receipt</label>
  <input type="file" name="receipt"  value="">
</div>

<div>
  <label id="label">When did you make the payment</label>
  <input type="date" name="payment_date" id="">
</div>
</div>
<div style="margin:10px;border-bottom:1px solid gray;padding:10px">
  <label id="label">Click to accept the <a href="terms.html" style="text-decoration:none;color:green">terms and conditions</a>
  <input type="checkbox" name="terms" value="accepted" required></label>
</div>
<input type="submit" value="Farm Now" id="subm">

</form>


</div>






<div class="account">


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

<script>
  
  document.getElementById("package").addEventListener("change", function() {
    document.getElementById("error").style.display = "none";
    calculateTotalCost();
  });

  document.getElementById("paymentPlan").addEventListener("change", function() {
    if (document.getElementById("package").value === "") {
      document.getElementById("error").style.display = "block";
    } else {
      document.getElementById("error").style.display = "none";
      calculateTotalCost();
    }
  });

  function calculateTotalCost() {
    var package = document.getElementById("package").value;
    var paymentPlan = document.getElementById("paymentPlan").value;
    var cost = 0;
    var returns = 0;

    if (package === "") {
      return; 
    }

    if (package === "10 Fishes") {
      cost = 13100;
      returns = 19000;
    } else if (package === "25 Fishes") {
      cost = 32750;
      returns= 47000;
    } else if (package === "50 Fishes") {
      cost = 65500;
      returns= 95000;
    } else if (package === "100 Fishes") {
      cost = 131000;
      returns= 190000;
    } else if (package === "200 Fishes") {
      cost = 262000;
      returns= 380000;
    } else if (package === "500 Fishes") {
      cost = 655000;
      returns=950000;
    } else if (package === "1,000 Fishes") {
      cost = 1310000;
      returns= 1900000;
    } else if (package === "2,000 Fishes") {
      cost = 2620000;
      returns=3800000;
    } else if (package === "5,000 Fishes") {
      cost = 6550000;
      returns= 9500000
    } else if (package === "10,000 Fishes") {
      cost = 13100000;
      returns= 19000000;
    } else if (package === "20,000 Fishes") {
      cost = 26200000;
      returns= 38000000;
    }

    var partPayment = 0;
    if (paymentPlan === "full_payment") {
      partPayment = cost;
    } else if (paymentPlan === "Weekly_Payment") {
      partPayment = cost / 4;
    } else if (paymentPlan === "Monthly_Payment") {
      partPayment = cost / 4;
    } else if (paymentPlan === "Daily_Payment") {
      partPayment = cost / 30;
    }

    document.getElementById("partPayment").value = partPayment;
    document.getElementById("totalCost").value = cost;
    document.getElementById("returns").value=returns;
  }

</script>

<?php
if (!empty($errorMessage)) {
    echo "<p style='color:red;text-align:center; position: fixed;
    bottom: 8%;
    right: 1% ;padding:20px'>$errorMessage</p>";
} elseif (!empty($successMessage)) {
    echo "<p style='color:green;text-align:center; position: fixed;
    bottom: 8%;
    right: 1% ;padding:20px'>$successMessage</p>";
}
?>
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