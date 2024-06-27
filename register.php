<?php

session_start();

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";
// Establish connection
$connection = mysqli_connect($servername, $username, $password, $database);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$referralCode = isset($_GET['ref']) ? $_GET['ref'] : '';
$referrerEmail = '';

// If referral code is present, fetch the referrer's email
if (!empty($referralCode)) {
    $refQuery = "SELECT email FROM users WHERE referralLink=?";
    $refStmt = mysqli_prepare($connection, $refQuery);
    mysqli_stmt_bind_param($refStmt, "s", $referralCode);
    mysqli_stmt_execute($refStmt);
    mysqli_stmt_bind_result($refStmt, $referrerEmail);
    mysqli_stmt_fetch($refStmt);
    mysqli_stmt_close($refStmt);
}

$errorMessage = '';
$successMessage = '';

// Function to generate a random alphanumeric string
function generateReferralCode() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $length = 10;
    $referralCode = '';
    for ($i = 0; $i < $length; $i++) {
        $referralCode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $referralCode;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['FullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $subscription = $_POST['subscription'];
    $dob = $_POST['DOB'];
    $stateOfResidence = $_POST['State_Of_Residence'];
    $password = $_POST['password'];
    $howDidYouHearAboutUs = $_POST['How_did_you_hear_About_Us'];

    if (empty($fullName) || empty($email) || empty($phone) ||  empty($address) ||empty($dob) || empty($stateOfResidence) || empty($password) || empty($howDidYouHearAboutUs)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $errorMessage = "Password must be at least 8 characters long and contain at least one capital letter and one number.";
    } else {
        $checkQuery = "SELECT * FROM users WHERE email=? OR phone=?";
        $checkStmt = mysqli_prepare($connection, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "ss", $email, $phone);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $errorMessage = "User with this email or phone number already exists.";
        } else {
            $targetFile = "";
            if (!empty($_FILES["image"]["name"])) {
                $targetDir = "uploads/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileType, $allowedTypes)) {
                    if (file_exists($targetFile)) {
                        $errorMessage = "Profile picture already exists.";
                    } else {
                        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                            $errorMessage = "Error uploading image";
                        }
                    }
                } else {
                    $errorMessage = "Please upload a valid image file";
                }
            }

            $referralCode = generateReferralCode();
            $referralLink = "http://gudfama.com/register.php?ref=$referralCode";
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (full_name, email, phone, subscription, dob, state_of_residence, password, how_did_you_hear_about_us, profile_picture, referralLink, address) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "sssssssssss", $fullName, $email, $phone, $subscription, $dob, $stateOfResidence, $hashedPassword, $howDidYouHearAboutUs, $targetFile, $referralLink, $address);
            if (mysqli_stmt_execute($stmt)) {
                $userId = mysqli_insert_id($connection);
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_full_name'] = $fullName;

                if (!empty($referrerEmail)) {
                    $referralInsert = "INSERT INTO referrals (referrer_email, referred_email, referred_name, referred_phone) 
                                       VALUES (?, ?, ?, ?)";
                    $referralStmt = mysqli_prepare($connection, $referralInsert);
                    mysqli_stmt_bind_param($referralStmt, "ssss", $referrerEmail, $email, $fullName, $phone);
                    mysqli_stmt_execute($referralStmt);
                    mysqli_stmt_close($referralStmt);
                }
               sendWelcomeEmail($fullName, $email);
                header("Location: please_wait.php");
                exit;
            } else {
                $errorMessage = "Error: " . mysqli_error($connection);
            }
        }
    }
}
function sendWelcomeEmail($fullName, $email) {
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
        $mail->addAddress($email, $fullName);
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
                    <h1>Welcome to Gudfama</h1>
                </div>
                <div class="content">
                    <p>Dear ' . htmlspecialchars($fullName) . ',</p>
                    <p>We at Gudfama heartily congratulate you on starting your farming journey with Gudfama.</p>
                    <p>To continue, log in to your dashboard and enter your preferred bank account details in the accounts page. Then head to the Farm page to start your farming.</p>
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
    <title>Register Now</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="reg.css">
    <link rel="shortcut icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
  <style>
      div{
         opacity: 0;
         transform: translateY(20px);
         transition: opacity 0.7s ease-out, transform 0.7s ease-out;
       }

       div.visible {
         opacity: 1;
         transform: translateY(0);
     }
 </style>
  <style>
    @media screen and (max-width:700px) {
    
    .container{
            position:absolute;
            top:-70px;
           }
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
    <main>
      <div id="register">
         <div id="up">
<h2>Registration</h2>
      </div>
      <div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <div>
      <label for="FullName">Full Name</label><br>
      <input type="text" name="FullName" id="FullName" value="<?php if(isset($_POST['FullName'])) echo $_POST['FullName']; ?>" required>
    </div>
    <div>
      <label for="email">Email</label><br>
      <input type="email" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
    </div>
    <div>
      <label for="phone">Phone Number</label><br>
      <input type="tel" name="phone" id="phone" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']; ?>" required>
    </div>
    <div>
      <label for="text">Address</label><br>
      <input type="tel" name="address" id="address" value="<?php if(isset($_POST['address'])) echo $_POST['address']; ?>" required>
    </div>
    <div>
      <label for="subscription">Are you an existing subscriber with Gudfama?</label><br>
      <select name="subscription" id="subscription" required>
        <option value="">Select option</option>
        <option value="yes" <?php if(isset($_POST['subscription']) && $_POST['subscription'] == 'yes') echo 'selected'; ?>>Yes</option>
        <option value="no" <?php if(isset($_POST['subscription']) && $_POST['subscription'] == 'no') echo 'selected'; ?>>No</option>
      </select>
    </div>
    <div>
      <label for="DOB">Date of Birth</label><br>
      <input type="date" name="DOB" id="DOB" value="<?php if(isset($_POST['DOB'])) echo $_POST['DOB']; ?>" required>
    </div>
    <div>
      <label for="State_Of_Residence">State of Residence</label><br>
      <input type="text" name="State_Of_Residence" id="State_Of_Residence" value="<?php if(isset($_POST['State_Of_Residence'])) echo $_POST['State_Of_Residence']; ?>" required>
    </div>
    <div>
      <label for="password">Set your password</label><br>
      <input type="password" name="password" id="password" required>
    </div>
    <div>
      <label for="How_did_you_hear_About_Us">How did you hear About Us</label><br>
      <select name="How_did_you_hear_About_Us" id="How_did_you_hear_About_Us" required>
        <option value="">Select option</option>
        <option value="Facebook" <?php if(isset($_POST['How_did_you_hear_About_Us']) && $_POST['How_did_you_hear_About_Us'] == 'Facebook') echo 'selected'; ?>>Facebook</option>
        <option value="WhatsApp" <?php if(isset($_POST['How_did_you_hear_About_Us']) && $_POST['How_did_you_hear_About_Us'] == 'WhatsApp') echo 'selected'; ?>>WhatsApp</option>
        <option value="Instagram" <?php if(isset($_POST['How_did_you_hear_About_Us']) && $_POST['How_did_you_hear_About_Us'] == 'Instagram') echo 'selected'; ?>>Instagram</option>
        <option value="Word_of_mouth" <?php if(isset($_POST['How_did_you_hear_About_Us']) && $_POST['How_did_you_hear_About_Us'] == 'Word_of_mouth') echo 'selected'; ?>>Word of Mouth</option>
        <option value="On Radio" <?php if(isset($_POST['How_did_you_hear_About_Us']) && $_POST['How_did_you_hear_About_Us'] == 'On Radio') echo 'selected'; ?>>On Radio</option>
        <option value="Referral" <?php if(isset($_POST['How_did_you_hear_About_Us']) && $_POST['How_did_you_hear_About_Us'] == 'Referral') echo 'selected'; ?>>Referral</option>
      </select>
    </div>
    
    <div>
      <label for="image">Select Profile Picture (Optional)</label><br>
      <input type="file" name="image"  accept="image/*">
    </div>
    <div>
      <input type="submit" value="Register" id="submit">
    </div>
  </form>
    </div>
      </div>
     
    </main>
    <svg style="margin: 0px;"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#128354" fill-opacity="1" d="M0,224L60,218.7C120,213,240,203,360,208C480,213,600,235,720,256C840,277,960,299,1080,282.7C1200,267,1320,213,1380,186.7L1440,160L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path></svg>
    
   

    <footer>
      <div class="foot">
        <div class="f1">
          <h3>About Us</h3>
      <p style="line-height: 23px;">GUDFAMA, a subsidiary of Business Gladius Africa (BGA), was established to address Nigeria and Africa's food crisis caused by rural-urban migration. Born in 2014, BGA has worked as a manufacturer's representative and marketing service provider, focusing on SMEs. In 2025, it expanded its efforts into the agribusiness sector with GUDFAMA.</p>
     </div>
      <div class="f1">
          <h3>Contact Us</h3>
          <p><b> <i class="fa fa-map-marker" style=" font-size:20px;color:#2CB67D;padding-right: 10px"></i>Farm:</b>o. 24 APA mini Street Off Y Junction,
            Miniorlu Ada-George.                                                                                                                             
          </p>
          <p><b> <i class="fa fa-map-marker" style=" font-size:20px;color:#2CB67D;padding-right: 10px"></i>Office:</b>  13 Rumuodaolu Market Road, Off Rumuola Road, Port Port-Harcourt
                                                                                                                                       
          </p>
          <p><b><i class="fa fa-phone" style="font-size:15px;color:#2CB67D;padding-right: 10px;"></i>Telephone:</b> 07042715386, 08069902316
          </p>
          <p><p><i class="fa fa-envelope" style="font-size:15px;color:#2CB67D;padding-right: 10px;"></i><b>Email:</b> info@gudfama.com
          </p>
        </div>
        <div class="f2">
          <h3>Quick links</h3>
         <p><i class="fa-solid fa-arrow-right"></i><a href="index.html">Home</a></p> 
         <p><i class="fa-solid fa-arrow-right"></i><a href="updates.php">Updates</a></p> 
         <p><i class="fa-solid fa-arrow-right"></i><a href="contact.html">Contact</a></p> 
         <p><i class="fa-solid fa-arrow-right"></i><a href="login.php">Dashboard</a></p> 
         <p><i class="fa-solid fa-arrow-right"></i><a href="register.php">Register</a></p> 
        </div>
        <div class="f2">
          <h3>Company</h3>
        <p><i class="fa-solid fa-arrow-right"></i><a href="about.html">Who we are</a></p>  
        <p><i class="fa-solid fa-arrow-right"></i><a href="staff.html">Our Team</a></p>  
        <p><i class="fa-solid fa-arrow-right"></i><a href="services.html">Our Services</a></p>  
        <p><i class="fa-solid fa-arrow-right"></i><a href="products.html">Our Products</a></p>  
        </div>
       
      </div>
      <div class="socials">
        <p>Copyright 2024 Gudfama.com</p>
        <div style="display: flex;flex-direction: row;justify-content: center;">
          <a href="#" style="margin-bottom: 10px;"><i class="fa fa-facebook-official" style="font-size:24px;color:#2CB67D"></i> </a>
          <a  href="#" style="margin-bottom: 10px;"><i class="fa-brands fa-x-twitter" style="font-size:24px;color:#2CB67D"></i></a>
          <a class="ml-3 text-gray-500" href="#" style="margin-bottom: 10px;"><i class="fa fa-instagram" style="font-size:24px;color:#2CB67D"></i></a>
      <a class="ml-3 text-gray-500" href="#" style="margin-bottom: 10px;"><i class="fa-brands fa-whatsapp" style="font-size:24px;color:#2CB67D"></i></a>
        </div>
      </div>
      </div>
    </footer>
        <!-- <div>
          <label for="">Select a package you wish to invest in</label><br>
          <select name="Package" id="">
 <option value="13,100 (10 Fishes)">13,100 (10 Fishes)</option>
<option value="32,750 (25 Fishes)">32,750 (25 Fishes)</option>
<option value="65,500 (50 Fishes)">65,500 (50 Fishes)</option>
<option value="131,000 (100 Fishes)">131,000 (100 Fishes)</option>
<option value="262,000 (200 Fishes)">262,000 (200 Fishes)</option>
<option value="655,000 (500 Fishes)">655,000 (500 Fishes)</option>
<option value="1,310,000 (1,000 Fishes)">1,310,000 (1,000 Fishes)</option>
<option value="2,620,000 (2,000 Fishes)">2,620,000 (2,000 Fishes)</option>
<option value="6,550,000 (5,000 Fishes)">6,550,000 (5,000 Fishes)</option>
<option value="13,100,000 (10,000 Fishes)">13,100,000 (10,000 Fishes)</option>
<option value="26,200,000 (20,000 Fishes)">26,200,000 (20,000 Fishes)</option>
          </select>
        </div> -->

             <!-- <div>
          <label for="">Payment Plan</label><br>
          <select name="Payment_plan" id="">
            <option value="full_payment">Full Payment</option>
            <option value="Weekly_Payment">Weekly Payment</option>
            <option value="Monthly_Payment">Monthly Payment</option>
            <option value="Daily_Payment">Daily Payment</option>
          </select>
        </div> -->
        <!-- <div>
          <label for="">Have you Made Payment </label><br>
          <select name="Have you Made Payment " id="">
            <option value="yes">Yes</option>
            <option value="no">No</option>
          </select>
        </div>
        <div>
          <label for="">If Yes:  Amount Paid</label><br>
          <input type="number" name="" id="">
        </div>
        <div>
          <label for="">Upload proof of payment</label><br>
          <input type="file" name="" id="">
        </div>
        <div>
          <p><span style="color: #2CB67D;font-weight: bold;">Note</span>: If you have not made payment,on clicking the Register button you will be redirected to the online payment page to complete payment </p> 
        </div> -->

        <?php
if ($errorMessage !== '') {
    echo '<div id="errorMessage" style="color: red;text-align:center; position: fixed;
            bottom: 5%;
            right: 1% ;;padding:20px">' . $errorMessage . '</div>';
}

if ($successMessage !== '') {
    echo '<div id="successMessage" style="color: #2CB67D;text-align:center; position: fixed;
            bottom: 5%;
            right: 1% ;:black;padding:20px" >' . $successMessage . '</div>';
}
?>

<script>
    // Function to hide error and success messages after 3 seconds
    setTimeout(function() {
        document.getElementById("errorMessage").style.display = "none";
        document.getElementById("successMessage").style.display = "none";
    }, 2000);
</script>
<script src="index.js"></script>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
  const sections = document.querySelectorAll('div');

  const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
          if (entry.isIntersecting) {
              entry.target.classList.add('visible');
          } else {
              entry.target.classList.remove('visible');
          }
      });
  }, {
      threshold: 0.1
  });

  sections.forEach(section => {
      observer.observe(section);
  });
});
    </script>
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