<?php
session_start();

if (!isset($_SESSION['user_email']) || empty($_SESSION['user_id'])) {
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

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$full_name = '';
$package = '';
$amountPaid = '';
$referralLink='';

// Retrieve account details from the database
$sql = "SELECT full_name, profile_picture, package, amountPaid, referralLink FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $full_name, $profile_picture, $package, $amountPaid, $referralLink);
    mysqli_stmt_fetch($stmt);
}


$sql = "SELECT * FROM referrals WHERE referrer_email = ?";
$stmt = mysqli_prepare($connection, $sql);

if ($stmt === false) {
    die("Failed to prepare statement: " . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "s", $user_email);
$result = mysqli_stmt_execute($stmt);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

mysqli_stmt_store_result($stmt);
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <style>
      table,tr,th,td{
        border: 1px solid gray;border-collapse: collapse;padding: 5px;
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
<p><i class="fa-solid fa-fire" style="padding-right: 6px;color: red;"></i><b>Refer and make money</b></p>
<hr>

<form action="" class="form">
    <input type="text" value="<?php echo $referralLink; ?>" id="link">
    <input type="submit" value="Copy share link" id="submit">
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit').addEventListener('click', function(event) {
        event.preventDefault();
        var link = document.getElementById('link').value;
        navigator.clipboard.writeText(link).then(function() {
            alert("Link copied to clipboard!");
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    });
});
</script>
<p>1. Copy the share link and send to your friends and family.</p>
<p style="color:rgb(233, 22, 90)">2. As long as the friends you invite register and subscribe to a package on our platform, you can get 5% commission as a reward.</p>
<p>3. Use our sharing guide to help you invite more people.</p>

</div>

<div id="invites">
<h3>Your Invitations <i class="fa-solid fa-caret-down"></i></h3>
<p>See who you've invited and their details below.</p>
<?php if (mysqli_stmt_num_rows($stmt) > 0) {
    // Bind result variables
    $meta = $stmt->result_metadata();
    $fields = $meta->fetch_fields();
    $result_data = [];

    foreach ($fields as $field) {
        $result_data[$field->name] = null;
        $bind_result[] = &$result_data[$field->name];
    }

    call_user_func_array([$stmt, 'bind_result'], $bind_result);

    // Fetch and display each row
    while (mysqli_stmt_fetch($stmt)): ?>
        <li>
            <table>
                <tr>
                    <th>Referral Name</th>
                    <th>Referral Email</th>
                    <th>Referral Phone</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($result_data['referral_name']); ?></td>
                    <td><?php echo htmlspecialchars($result_data['referral_email']); ?></td>
                    <td><?php echo htmlspecialchars($result_data['referral_phone']); ?></td>
                </tr>
            </table>
        </li>
    <?php endwhile;
} else {
    echo "No records found.";
}
mysqli_stmt_close($stmt);
?>
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