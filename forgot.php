<?php
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the POST request and sanitize it
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    $servername = 'sdb-72.hosting.stackcp.net';
    $username = "gudfama";
    $password = "Nikido886@";
    $database = "gudfama-35303631fafd";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists in the accounts table
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Create a unique token for the password reset link
            $token = bin2hex(random_bytes(32));
            $resetLink = "https://gudfama.com/password_reset.php?token=$token";

            // Save the token in the database with an expiration date
            $tokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour'));
            $insertTokenQuery = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($insertTokenQuery);
            $stmtInsert->bind_param("sss", $email, $token, $tokenExpiry);
            $stmtInsert->execute();

            // Send the email using PHPMailer
            if (sendPasswordResetEmail($email, $resetLink)) {
                echo "Reset password link sent, please check your email";
                echo '<meta http-equiv="refresh" content="3;url=login.php">';
                exit;
            } else {
                echo "Error sending email. Please try again later.";
            }
        } else {
            echo "No account found for this email. Please enter a valid email.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement.";
    }

    // Close the database connection
    $conn->close();
}

function sendPasswordResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);

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
        $mail->addAddress($email);
        $mail->addReplyTo('info@gudfama.com', 'Gudfama');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
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
                    <h1>Password Reset Request</h1>
                </div>
                <div class="content">
                    <p>Dear User,</p>
                    <p>You requested to reset your password. Please click the link below to reset your password:</p>
                    <p><a href="' . htmlspecialchars($resetLink) . '">Reset Password</a></p>
                    <p>If you did not request a password reset, please ignore this email.</p>
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
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
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
<style>
  
     #div{
        text-align: center;
        margin: 30px;padding: 20px;color:#fff;border-radius: 10px;
     }
     form>input{
        padding: 10px;margin: 10px;border: 1px solid rgb(221, 221, 221);border-radius: 10px;
     }
     #submit{
        background-color: #2CB67D;color: white;
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
          <h4>Password reset</h4>
       </div>
       <div id="div">
        <p>Enter email associated with your account to receive password reset link</p>
        <form action=" " method="post">

            <input type="email" name="email" id="email" placeholder="Email" required><br>
            <input type="submit" id="submit">
           
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