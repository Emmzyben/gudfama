<?php
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

// SQL query to retrieve posts with category 'blog' sorted by the latest post
$sql_blog = "SELECT * FROM posts WHERE category = 'blog' ORDER BY post_date DESC";
$result_blog = mysqli_query($connection, $sql_blog);

// Check if the query was successful
if (!$result_blog) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch posts with category 'blog' into an array
$blog_posts = [];
while ($row = mysqli_fetch_assoc($result_blog)) {
    $blog_posts[] = $row;
}

// SQL query to retrieve posts with category 'announcement' sorted by the latest post
$sql_announcements = "SELECT * FROM posts WHERE category = 'announcement' ORDER BY post_date DESC";
$result_announcements = mysqli_query($connection, $sql_announcements);

// Check if the query was successful
if (!$result_announcements) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch posts with category 'announcements' into an array
$announcements_posts = [];
while ($row = mysqli_fetch_assoc($result_announcements)) {
    $announcements_posts[] = $row;
}


mysqli_close($connection);

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest updates</title>
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <style>
    
    .post-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
         
        }
        #cool{
          padding: 10px;
        } 
       #cooler{
          margin-bottom: 10px;
        }
        #cooler>h3{
            background-color:#2CB67D;
            font-size: 13px;
            padding: 7px;
            color: white;
            border-radius: 5px;
          }
         #cooler>p{
          font-size: 13px;
          color: grey;
         }
        #color{
          color:grey;
          font-size: 19px;
        }
        #cooler a{
          color:#08b197;
          font-size: 16px;
        }
        #cooler a:hover{
          color:#ff1b00;
        }
 #img1 {
           width: 400px;
           height: 300px;
           margin-left: 0;
            margin-top:10px;
            object-fit:fill;
            border-radius: 5px;
        }
        ul li{
  list-style-type: none;
}

#ul{
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  justify-content: left;
  width: 96%;
}
#ul>li{
border-bottom: 1px solid rgb(218, 212, 212);
  margin: 10px;
  width:auto;
}
        .newIMG{
             width:70px;
                margin-top:-40px;
                margin-left:10px;
                }
@media screen and (max-width:600px) {
  #updates{
    flex-direction:column;
  }
#ul{
  flex-direction: column;
  justify-content: center;
  flex-wrap: nowrap;
  margin: 0;
  padding: 0;
}
#ul>li{
  width: auto;
  margin: 10px;
  margin-bottom: 20px;
}
  #img1{
    width:100%;
    height: 250px;
margin: 0;
  }
       
  
}
</style>

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
<h3 style="text-align:center;text-decoration:underline">Latest Updates</h3>
<div class="post-container"> 
    <ul id="ul">
        <?php foreach ($blog_posts as $row): ?>
            <li id='cool'>
                <?php if (!empty($row['file'])): ?>
                    <?php $fileExtension = pathinfo($row['file'], PATHINFO_EXTENSION); ?>
                    <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src='adminPage/<?php echo $row['file']; ?>' alt='Post Image' id='img1'>
                    <?php elseif (in_array($fileExtension, ['mp4', 'avi', 'mpeg'])): ?>
                        <video controls style="max-width: 100px; max-height: 100px;">
                            <source src="adminPage/<?php echo $row['file']; ?>" type="video/<?php echo $fileExtension; ?>" id='img1'>
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                <?php endif; ?>

                <div id='cooler'>
                    <h1 id='color'><b><?php echo htmlspecialchars($row['title']); ?></b></h1>
                    <p><?php echo nl2br($row['content']); ?></p>
                    <p>by <?php echo htmlspecialchars($row['author']); ?></p>
                    <p>Posted on: <?php echo htmlspecialchars($row['post_date']); ?></p>
                    <h3>Category: <?php echo htmlspecialchars($row['category']); ?></h3>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>


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