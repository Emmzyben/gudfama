<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'gudfama';


$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$login_error="";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $con->prepare('SELECT user_id, username, password, role FROM adminUser WHERE username = ? AND role = ?');
    $stmt->bind_param('ss', $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the entered password matches the stored plain text password
        if ($password === $row['password']) {
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // No need for redirection here, just set a flag for successful login
            $login_success = true;
        } else {
            $login_error = 'Incorrect username, password, or role!';
        }
    } else {
        $login_error = 'Incorrect username, password, or role!';
    }

    $stmt->close();
}

// Redirect based on successful login or display login error
if (isset($login_success) && $login_success) {
    switch ($role) {
        case 'staff':
            header('Location: adminPage/admin.php');
            exit;
        case 'pr':
            header('Location: adminPage/PR.php');
            exit;
        case 'accounts':
            header('Location: adminPage/accountsPage.php');
            exit;
        default:
            $login_error = 'Invalid role!';
            break;
    }
} elseif (isset($login_error)) {
    echo $login_error;
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="home.css">
    <link rel="shortcut icon" href="images/logo.jpg">
    <style>
     body{
        display: flex;
        align-items: center;
        justify-content: center;
     }
#form{
  display: flex;
  flex-direction: column;text-align: center;
  margin: 20px;box-shadow: 1px 1px 10px rgb(240, 236, 236);border-radius: 10px;
}
#form>div{
  width: auto;
}
#f1{
  padding: 10px;
  background-color: #2CB67D;
  font-size: larger;
  color: white;border-top-right-radius: 10px;
  border-top-left-radius: 10px;
}
#f2{
  padding: 10px;
  background-color: rgb(255, 255, 255);
  border-bottom-right-radius: 10px;
  border-bottom-left-radius: 10px;
}
#form a{
  color: #2CB67D;
  text-decoration: none;
  font-weight: 500;
}
#form p{
  color: #ff1b00;
}
form>input,select{
  margin: 10px;
  padding: 10px;
  width: 300px;
  border: 1px solid rgb(228, 223, 223);
  border-radius: 10px;
}

#subm{
  background-color: #2CB67D;
  color: white;
}
    </style>
</head>
<body>
  
    <main>
   <div id="form">
<div id="f1"><h2>Admin Login</h2></div>
<div id="f2">
  <form action="" method="post">
    <input type="text" name="username" id="" placeholder="Username"><br>
    <input type="password" name="password" id="" placeholder="Password"><br>
    <select name="role" id="">
      <option value="">Login as</option>
      <option value="staff">Staff</option>
  <option value="pr">PR Team</option>
  <option value="accounts">Accounts</option>
    </select><br>
    <input type="submit" name="" id="subm" value="Login"><br>
    <a href="index.html">Go to Home page</a>
  </form>
</div>
   </div>
    </main>
 

    <?php
if ($login_error !== '') {
    echo '<div id="errorMessage" style="color: red;text-align:center; position: fixed;
            bottom: 5%;
            right: 1% ;;padding:20px">' . $login_error . '</div>';
}


?>
    <script src="script.js"></script>
</body>
</html>