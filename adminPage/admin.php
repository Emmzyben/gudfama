
<?php
session_start();

// Check if admin is not logged in, then redirect to adminlogin.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: adminlogin.php');
    exit;
}
function checkAccess($allowed_roles) {
  if (!isset($_SESSION['role'])) {
      header('Location: forbidden.php');
      exit;
  }

  if (!in_array($_SESSION['role'], $allowed_roles)) {
      header('Location: forbidden.php');
      exit;
  }
}

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT COUNT(*) AS total_users FROM users";
$result = mysqli_query($connection, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalUsers = $row['total_users'];
} else {
    die("Query failed: " . mysqli_error($connection));
}

$packages = [
  '10 Fishes',
  '25 Fishes',
  '50 Fishes',
  '100 Fishes',
  '200 Fishes',
  '500 Fishes',
  '1,000 Fishes',
  '2,000 Fishes',
  '5,000 Fishes',
  '10,000 Fishes',
  '20,000 Fishes'
];

$subscribers = [];

foreach ($packages as $package) {
  $sql = "SELECT COUNT(*) AS total_subscribers FROM users WHERE package = ?";
  $stmt = mysqli_prepare($connection, $sql);
  mysqli_stmt_bind_param($stmt, 's', $package);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $totalSubscribers);
  mysqli_stmt_fetch($stmt);
  $subscribers[$package] = $totalSubscribers;
  mysqli_stmt_close($stmt);
}

mysqli_close($connection);
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="../images/logo.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="admin.css">
        <style>
              #inmain>div{
                box-shadow: 1px 1px 10px rgb(219, 218, 218);
                padding: 10px;
                margin-bottom: 10px;overflow: auto;
            }

        </style>
    </head>
    <body>
    <aside >
            <div style="text-align: center;padding-top: 15px;color: #012A6A;padding-left: 20px;"><h3>Gudfama Admin</h3></div>
            <div  id="span" onclick="openNav()" style="cursor: pointer;">&#9776;</div>
          </aside>
          <nav>
            <div id="mySidenav" class="sidenav">
            
            <img src="../images/logo.png" alt="" id="img"><hr>
            <a href="admin.php" >Overview</a>
           <a href="accountspage.php" >Accounts department</a>
            <a href="PR.php" >PR department</a>
            <a href="adminlogout.php" >Logout</a>
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
    <main>
    <div id="show">
            <div id="side">
                <div style="text-align:center">
                <span style="padding-left: 10px;"><h3>Gudfama</h3></span>
                </div>
                <div style="text-align: center;"><p>Admin Dashboard</p></div>
                <hr>
                <ul>
                    <li ><a href="admin.php" id="focus1">Overview</a></li>
                    <li ><a href="accountspage.php" id="focus2">Accounts department</a></li>
                    <li ><a href="PR.php" id="focus2">PR department</a></li>
                    <li><a href="adminlogout.php" id="focus2">Logout</a></li>
                </ul>
            </div>  
    </div>
     
            
    <div style="padding: 20px;overflow:auto" id="main" >
         <div>
        <h2 style="color:white;background-color:#2CB67D ;padding:10px">Overview</h2>
        <div id="inmain">
        <div>
          <p>Total Subscribers: <?php echo htmlspecialchars($totalUsers); ?></p>
        </div>
        <div>
        <p>Total Subscribers for each Package</p>
        <ul>
        <?php foreach ($subscribers as $package => $count): ?>
            <li><?php echo htmlspecialchars($package) . ': ' . htmlspecialchars($count); ?></li>
        <?php endforeach; ?>
    </ul>
        </div>

        </div>
            
      
     
            
            
            
    </div>  
    
    </main>
      
    
             
    
    <script>
    function checkRole(role) {
        <?php if (isset($_SESSION['role'])): ?>
            var userRole = "<?php echo $_SESSION['role']; ?>";
            if ((role === 'pr' && userRole !== 'pr') ||
                (role === 'accounts' && userRole !== 'accounts') ||
                (role === 'staff' && userRole !== 'staff')) {
                alert('You do not have permission to access this page.');
                return false;
            }
        <?php endif; ?>
        return true;
    }
</script>
    </body>
    </html>

