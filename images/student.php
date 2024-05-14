<?php
session_start();
$_SESSION['last_activity'] = time();

$servername = 'localhost';
$username = "grafordc_graford";
$password = "Gratia12345";
$database = "grafordc_graford";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to store user input and error message
$userInputMatnumber = "";
$userInputPassword = "";
$errorMsg = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $userInputMatnumber = $_POST["Matnumber"];
    $userInputPassword = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM accounts WHERE accreditationNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userInputMatnumber);
    $stmt->execute();
    
    // Bind variables to the result set
    $stmt->bind_result($id, $fullName, $state, $nationality, $dateOfBirth, $email,$course, $phone, $address, $maritalStatus, $religion, $qualification, $passportImagePath,$accreditationNumber, $created_at, $password);

    if ($stmt->fetch()) {
        // User found, compare the plain password
        if ($userInputPassword == $password) {
            // User authentication successful
            $_SESSION['Matnumber'] = $userInputMatnumber; 
            $_SESSION['last_activity'] = time();
            echo '<script>setTimeout(function() { window.location = "overview1.php"; }, 1500);</script>';
        } else {
            // User authentication failed
            $errorMsg = "Authentication failed. Please check your matric number and password.";
        }
    } else {
        // User not found
        $errorMsg = "Authentication failed. Please check your matric number and password.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

</head>
<body>
<header id="cover">
        <div style="background-color: #ffffff;">
            
               <div style="display: flex;flex-direction: row;" id="name">
                   <span id="side"><img src="images/logo.jpg" alt="logo"  ></span>
                    <span id="center">
                      <h1>EMI<span style="color: #2154cf;">BLAQ</span> TECH SO<span style="color: #2154cf;">LUT</span>IONS</h1>
                      <p><i class="fa fa-phone-square"></i> +234 (0) 9056897432 <span id="sp"><i class="fa fa-phone-square"></i> +234 (0) 7085082556 </span><span id="sp"><a style="text-decoration: none;color: rgb(98, 108, 132);" href="mailto:support@emiblaqtech.com"><i class="fa fa-share-square-o"></i> support@emiblaqtech.com</a></span></p>
                   </span>
                   <span id="side" style="text-align:right;">
                       <ul>
                       <li><a href="register.php">REGISTER NOW!</a></li>
                       <li><a href="contact.html">CONTACT US</a></li>
                       </ul>
                   </span>
               </div>
    
              </div>
    </header> 

        
             <div id="bg">
                 <ul>
                     <li><a href="index.html">HOME</a></li>
                         <li><a href="about.html">ABOUT US</a></li> 
                         <li class="nav-container">
                            <span id="hoverer">SERVICES</span> 
                             <ul id="dropdown">
                              <li><a href="#">Web Design</a></li> 
                              <li><a href="#">Web development</a></li> 
                              <li><a href="#">Website maintenance</a></li>
                              <li><a href="#">Graphic design</a></li>
                             </ul>
                           </li>
                           <li class="nav-container">
                            <span id="hoverer">ACADEMY COURSES</span> 
                            <ul id="dropdown">
                              <li><a href="academy.html">Web development<br>(Html,Css,Javascript)</a></li> 
                              <li><a href="academy.html">Full-stack development<br>(Front-end,Back-end)</a></li> 
                              <li><a href="academy.html">Graphic design</a></li>
                             </ul>
                           </li>
                          <li><a href="portfolio.html">PORTFOLIO</a></li>
                          <li><a href="packages.html">OUR PACKAGES</a></li>
                          <li><a href="student.php">STUDENT PORTAL</a></li> 
                           <li><a href="blog.php">NEWS/BLOG</a></li>  
                 </ul>
             </div>
     

    
    <aside style="z-index: 1;">
        <div style="width: 20%;"><img src="images/logo.jpg" alt="logo" ></div>
       <div onclick="openNav()" >
          <div class="container" onclick="myFunction(this)" id="sideNav">
              <div class="bar1"></div>
              <div class="bar2"></div>
              <div class="bar3"></div>
            </div>
          </div>
    </aside>

      



<nav>
    <div id="mySidenav" class="sidenav">

      <img src="images/logo.jpg" alt="" id="img"><hr>
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a class="dropdown-item" onclick="toggleDropdown()" >
       Services +
          <div class="sub-menu1" style="display: none;transition: 0.5s;background-color: #d3e4ee;
          color: #fff;">
         <a href="#">Web Design</a>
          <a href="#">Web development</a>
          <a href="#">Website maintenance</a>
          <a href="#">Graphic design</a>
          </div>
        </a>
     
        <script>
          function toggleDropdown() {
            const subMenu = document.querySelector('.sub-menu1');
            subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
          }
        </script>
   <a class="dropdown-item" onclick="toggleDropdown2()">
   Academy Courses +
   <div class="sub-menu2" style="display: none;transition: 0.5s;background-color: #d3e4ee;
       color: #fff;">
   <a href="academy.html">Web development<br>(Html,Css,Javascript)</a>
   <a href="academy.html">Full-stack development<br>(Front-end,Back-end)</a>
  <a href="academy.html">Graphic design</a>
       </div>
     </a>
  
     <script>
       function toggleDropdown2() {
         const subMenu2 = document.querySelector('.sub-menu2');
         subMenu2.style.display = (subMenu2.style.display === 'none' || subMenu2.style.display === '') ? 'block' : 'none';
       }
     </script>

                  <a href="portfolio.html">Portfolio</a>
                        <a href="packages.html">Our packages</a>
                        <a href="student.php">Student portal</a>
                        <a href="blog.php">News/Blog</a>
                       <a href="contact.html"> Contact Us</a>
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



<div id="container">
  <div class="hide"></div>
  <div style="margin: 20px;" id="border">
  <h2 style="border-left: 0px solid white;">Student Portal Login</h2>

<form action="" id="formdiv" method="post">
  <label for="">Mat number <span>*</span></label><br>
<input type="text" name="Matnumber" id=""><br>
  <label for="">Password<span>*</span></label><br>
<input type="password" name="password"><br>
<a href="password_reset.php" style="padding-left: 5px;">Forgot Password?</a><br><br>
<button type="submit"  id="submit">Login</button>
<?php
if (!empty($errorMsg)) {
    echo '<p style="color: red;">' . $errorMsg . '</p>';
}
?>
</form>

</div>
<div class="hide"></div>
</div>













<div id="overfoot">
  <footer>
<div style="display: flex;flex-direction: row;">
  <div><img src="images/logo.jpg" alt="logo" width="80px"></div>
<div><h3 style="padding: 10px;color: white;">EMIBLAQ TECH SOLUTIONS</h3></div>
</div>
<hr>
<div id="foot">
<div>
<h3>ABOUT</h3>
<p><a href="about.html">About Us</a></p>
<h3>ACADEMY COURSES</h3>
<p><a href="#">Web development<br>(Html,Css,Javascript)</a></p> 
<p><a href="#">Full-stack development<br>(Front-end,Back-end)</a></p> 
<p><a href="#">Graphic design</a></p>
</div>

 <div>
            <h3>SERVICES</h3>
            <P><a href="#">Web Design</a></P> 
            <P><a href="#">Web development</a></P> 
            <P><a href="#">Website maintenance</a></P>
            <P><a href="#">Graphic design</a></P>
          </div>

<div>
<h3>INFORMATION CENTER</h3>
<p><a href="blog.php">News and Blog</a></p>
<p><a href="packages.html">Our packages</a></p>
<p><a href="portfolio.html">Our Portfolio</a></p>

</div>

<div>
  <h3>CONTACT</h3>
  <p><a style="color: white;text-decoration: none;" href="mailto:support@emiblaqtech.com"><i class="fa fa-share-square-o"></i> support@emiblaqtech.com</a></p>
  <p><i class="fa fa-phone-square" ></i> +234 (0) 9056897432</p>
  <p><i class="fa fa-phone-square" ></i> +234 (0) 7085082556</p>
</div>
</div>
<hr>
<div style="text-align: center;">
  <p>©2023  Emiblaq Tech- All rights reserved</p>
</div>
</footer>
</div>
    <script src="javascript.js"></script>
</body>
</html>