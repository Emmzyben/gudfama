<?php

session_start();

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

// Create a database connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$errorMessage = '';
$successMessage ='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Input validation
    if (empty($email) || empty($password)) {
        $errorMessage = "Email and password are required.";
    } else {
        // Check if user exists
        $checkQuery = "SELECT * FROM users WHERE email=?";
        $checkStmt = mysqli_prepare($connection, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_full_name'] = $user['full_name'];
                header("refresh:3; url=dashboard.php");
                exit();
            } else {
                $errorMessage = "Incorrect password.";
            }
        } else {
            $errorMessage = "User with this email does not exist.";
        }
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Now</title>
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
<style>
     #form {
    opacity: 0;
    transition: opacity 2s ease;
  }
</style>
  </head>
<body>
  <header>
    <div class="h1">
       <img src="images/logo.png" alt="">
    </div>
    <div class="h2">
       <ul>
      <li><a href="index.html">Home</a></li> 
       <li class="nav-container">
           <span id="hoverer">About</span> 
            <ul id="dropdown">
             <li><a href="about.html">Who we are</a></li> 
             <li><a href="staff.html">Our Team</a></li> 
            </ul>
           </li>
      <li class="nav-container">
       <span id="hoverer">Services/Products</span> 
       <ul id="dropdown">
           <li><a href="services.html">Our Services</a></li> 
           <li><a href="Products.html">Our Products</a></li> 
          </ul>
      </li>
      <li class="nav-container">
       <span id="hoverer">Gallery</span> 
        <ul id="dropdown">
            <li><a href="photos.php">Photos</a></li> 
            <li><a href="videos.php">Videos</a></li> 
           </ul>
      </li>
      <li><a href="updates.php">Updates</a></li>
      <li><a href="contact.html">Contact</a></li> 
      
    </ul>
    </div>
    <div class="h3">
       <ul>
           <li><a href="login.php">Login</a></li> 
           <li id="reg"> <a href="register.php">Register</a></li></ul>
    </div>
   </header>
   <aside> 
       <div>
         <img src="images/logo.png" alt="" >
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
           <img src="images/logo.png" alt="">
           <a href="index.html">Home</a>
           <a class="dropdown-item" onclick="toggleDropdown()" >
               About Us +
                  <div class="sub-menu1" style="display: none;transition: 0.5s;background-color: #2e2e33;
                  color: #fff;">
                 <a href="about.html">Who we are</a>
                 <a href="staff.html">Our Team</a>
                  </div>
                </a>
             
                <script>
                  function toggleDropdown() {
                    const subMenu = document.querySelector('.sub-menu1');
                    subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
                  }
                </script>
                 <a class="dropdown-item" onclick="toggleDropdown1()" >
                   Services and Products +
                      <div class="sub-menu2" style="display: none;transition: 0.5s;background-color: #2e2e33">
                       <a href="services.html">Our Services</a>
                       <a href="products.html">Our Products</a>
                      </div>
                    </a>
                 
                    <script>
                      function toggleDropdown1() {
                        const subMenu2 = document.querySelector('.sub-menu2');
                        subMenu2.style.display = (subMenu2.style.display === 'none' || subMenu2.style.display === '') ? 'block' : 'none';
                      }
                    </script>
                    <a class="dropdown-item" onclick="toggleDropdown3()" >
                     Gallery +
                                                <div class="sub-menu3" style="display: none;transition: 0.5s;background-color: #2e2e33">
                          <a href="photos.php">Photos</a>
                          <a href="videos.php">Videos</a>
                         </div>
                      </a>
                   
                      <script>
                        function toggleDropdown3() {
                          const subMenu3 = document.querySelector('.sub-menu3');
                          subMenu3.style.display = (subMenu3.style.display === 'none' || subMenu3.style.display === '') ? 'block' : 'none';
                        }
                      </script>
         <a href="updates.php">updates</a>
         <a href="contact.html">Contact Us</a>
         <a href="login.php" >Sign In</a>
           <a href="register.php">Sign UP</a>
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
    <main style="background-image: url(images/bg2.png);background-position: center;background-repeat: no-repeat;background-size: cover;position: relative;height: 600px;">
      <div style="position: absolute;top: 0;right: 0;left: 0;bottom: 0;height: auto;background-color: rgba(0, 0, 0, 0.493);" >
      <div id="login">
        <div style="text-align: center;color: white;margin: 20px;background-color: #2CB67D;border-radius: 10px;">
          <h4>Login to Dashboard</h4>
       </div>
       
          <div id="apply">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="form">

              <input type="email" name="email" id="email" placeholder="Email or Phone"><br>
              <input type="password" name="password" id="password" placeholder="Password"><br>
              <a href="forgot.html">Forgot Password?</a><br>
              <input type="submit" name="" id="submit"><br>
              <p style="text-align: center;color: white;">Not a member?<a href="register.php">Register Now!</a></p>
             
            </form>
      </div>  
    </div>
      <script>
         document.addEventListener("DOMContentLoaded", function() {
                  document.getElementById("form").addEventListener("submit", function(event) {
                      if (!validateForm()) {
                          event.preventDefault(); // Prevent form submission
                      }
                  });
      
                  function validateForm() {
                      var email = document.getElementById("email").value;
                      var password = document.getElementById("password").value;
      
                      // Check if any of the fields are empty
                      if ( email === '' || password === '') {
                        document.getElementById("box").innerHTML="Please fill in all fields"
                          return false; // Prevent form submission
                      }
      
                      return true; // Allow form submission
                  }
              });
      
              window.onload = function() {
                setTimeout(function() {
                  var applyDiv = document.getElementById('form');
                  if (applyDiv) {
                    applyDiv.style.opacity = '1';
          
                    requestAnimationFrame(function() {
                      applyDiv.style.transition = 'opacity 2s ease';
                    });
                  }
                }, 700); 
              };
            </script> 
      </div>
      </main>
      
    <footer></footer>
    <?php
if ($errorMessage !== '') {
    echo '<div id="errorMessage" style="color: red;text-align:center; position: fixed;
            bottom: 5%;
            right: 1% ;padding:20px">' . $errorMessage . '</div>';
}

if ($successMessage !== '') {
    echo '<div id="successMessage" style="color: #2CB67D;text-align:center; position: fixed;
            bottom: 5%;
            right: 1% ;padding:20px" >' . $successMessage . '</div>';
}
?>

<script>
    // Function to hide error and success messages after 3 seconds
    setTimeout(function() {
        document.getElementById("errorMessage").style.display = "none";
        document.getElementById("successMessage").style.display = "none";
    }, 2000);
</script>
     <div id="box" style="color:white;text-align:center; position: fixed;
            bottom: 5%;
            right: 5% ;;padding:20px" ></div>
</body>
</html>