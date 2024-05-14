<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dash.css">
</head>
<body>
    <!-- <div id="container">
<div id="wait">
        <h4>Welcome</h4>
        <p>Please wait..</p>
        <div class="circle"></div>
    </div>
</div> -->

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



<div id="client">
<div id="client1">
    <div>
     <img src="images/manager.png" alt="">
    </div>
    <div>
    <p>Hello Emmanuel Amadi</p>
      <p>My Package</p> 
      <p>Disbursement Date</p>
      <p>Total Referal</p>
    </div>
</div>
</div>

<div class="refer">
<p><i class="fa-solid fa-fire" style="padding-right: 6px;color: red;"></i><b>Refer and make money</b></p>
<hr>

<form action="" class="form">
    <input type="text" value="gudfama.com/register/782648720647sdf" id="link">
    <input type="submit" value="Copy share link" id="submit">
</form>

<p>1. Copy the share link and send to your friends and family.</p>
<p style="color:rgb(233, 22, 90)">2. As long as the friends you invite register and subscribe to a package on our platform, you can get 5% commission as a reward.</p>
<p>3. Use our sharing guide to help you invite more people.</p>

</div>

<div id="invites">
<h3>Your Invitations <i class="fa-solid fa-caret-down"></i></h3>
<p>See who you've invited and their statistics below.</p>
</div>

<div id="earn">
<h2 style="color:#2CB67D">Earn on the go!</h2>
<p>Choose any option below and start earning</p>


<!-- invest methods -->
<a>
    
    <div><img src="images/invest.png" alt="">
<h3>Invest</h3>
<p>Choose any of our farming packages and start earning</p></div>
    <div>
    <i class="fa-solid fa-arrow-right"></i>
    </div>
</a>


<a>
    
    <div><img src="images/invite.png" alt="">
<h3>Invite to register</h3>
<p>Start inviting your friends, family or followers, you can share your invitation link via text message or on social media. </p></div>
    <div>
    <i class="fa-solid fa-arrow-right"></i>
    </div>
</a>
</div>










</div>








    <!-- <script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var waitDiv = document.getElementById('container');
        var dashboard =document.getElementById('dashboard');
        waitDiv.style.display = 'block';
        dashboard.style.display = 'none';
        setTimeout(function(){
            waitDiv.style.display = 'none';
            dashboard.style.display = 'block';
        }, 4000); 
        
    });
</script> -->
</body>
</html>