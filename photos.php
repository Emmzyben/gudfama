<?php
$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify the SQL query to only select entries with the category 'picture'
$sql = "SELECT description, file FROM gallery WHERE category = 'picture' ORDER BY post_date DESC";
$result = $conn->query($sql);

$html = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $picture_path = $row['file'];
        $description = $row['description'];

        $html .= '<div class="image-container" style="border:1px solid #e4e4e4;border-radius:10px;">';
        $html .= '<img src="adminPage/' . $picture_path . '" style="height: 300px; width: 100%;">';
        $html .= '<p style="margin-top:20px;text-align:center"><b>' . $description . '</b></p>';
        $html .= '</div>';
    }
} else {
    $html = '<p>No images found.</p>';
}

// Close the database connection
$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photos</title>
    <link rel="stylesheet" href="index.css">
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
    <div id="Gallery">
        <h3>Photo Gallery</h3>
 
        <div class="image-gallery" style="display: flex; flex-direction: row; flex-wrap: wrap;justify-content:space-around">
    <?php echo $html; ?>
</div>
         </div>
   </main>
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#128354" fill-opacity="1" d="M0,224L60,218.7C120,213,240,203,360,208C480,213,600,235,720,256C840,277,960,299,1080,282.7C1200,267,1320,213,1380,186.7L1440,160L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path></svg>
    
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