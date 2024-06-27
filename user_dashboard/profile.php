<?php
session_start();

if (!isset($_SESSION['user_email']) || empty($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$errorMessage = $successMessage = "";
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$full_name = $email = $phone = $dob = $state_of_residence = $profile_picture = $registration_date = "";


$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle password change
    if (isset($_POST['password']) && isset($_POST['confirm_password']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if passwords match
        if ($password === $confirm_password) {
            // Hash the password before updating
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update password in the users table
            $updateSql = "UPDATE users SET password=? WHERE email=?";
            $updateStmt = mysqli_prepare($connection, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "ss", $hashed_password, $user_email);

            if (mysqli_stmt_execute($updateStmt)) {
                $successMessage = "Password changed successfully.";
            } else {
                $errorMessage = "Error updating password: " . mysqli_error($connection);
            }

            mysqli_stmt_close($updateStmt);
        } else {
            $errorMessage = "Passwords do not match.";
        }
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

// Fetch user data from the database
$sql = "SELECT full_name, email, phone, dob, state_of_residence, profile_picture, registration_date FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $full_name, $email, $phone, $dob, $state_of_residence, $profile_picture, $registration_date);
    mysqli_stmt_fetch($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <link rel="stylesheet" href="profile.css">
    <style>
      #image{
        width: 100px;
        height: 100px;
        border-radius: 50px;
      }
      td{
        font-size: 13px;border-bottom: 1px solid rgb(207, 206, 206);padding: 10px;
      }
      table{
        border: none;
      }
      table,tr{
    
    width: 80%;
    
}
    </style>
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

    <div class="account">
   
<div>
<h3>My Profile</h3>
<table>
  <tr>
   <td>Display Picture</td>
   <td> <?php if (!empty($profile_picture)) : ?>
            <img src="../<?php echo $profile_picture; ?>" alt="Profile Picture" id="image">
        <?php endif; ?></td>
  </tr>
  <tr>
<td>Name:</td>
<td><?php echo $full_name; ?></td>
  </tr>
  <tr>
<td>Email:</td>
<td><?php echo $email; ?></td>
  </tr>
  <tr>
<td>Phone number:</td>
<td><?php echo $phone; ?></td>
  </tr>

  <tr>
<td>Date of birth:</td>
<td><?php echo $dob; ?></td>
  </tr>
  <tr>
<td>State of residence:</td>
<td><?php echo $state_of_residence; ?></td>
  </tr> <tr>
<td>Registration date:</td>
<td><?php echo $registration_date; ?></td>
  </tr>
</table>

</div>

</div>

<div class="account" style="margin-top:-120px">
  <div id="refer">
    <h3>Change password</h3>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <input type="password" name="password" placeholder="Enter new password"><br>
    <input type="password" name="confirm_password" placeholder="Confirm password"><br>
    <input type="submit" name="submit" id="submit" value="Change">
  
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

 <div >
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
 </div>
</body>
</html>