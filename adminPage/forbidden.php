<?php
session_start();

// Check if admin is not logged in, then redirect to adminlogin.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: adminlogin.php');
    exit;
}
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="../images/logo.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>forbidden</title>
        <link rel="stylesheet" href="admin.css">
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
                    <li ><a href="admin.php" id="focus2">Overview</a></li>
                    <li ><a href="accountspage.php" id="focus2">Accounts department</a></li>
                    <li ><a href="PR.php" id="focus2">PR department</a></li>
                    <li><a href="adminlogout.php" id="focus2">Logout</a></li>
                </ul>
            </div>  
    </div>
     
            
    <div style="padding: 20px;overflow:hidden" id="main" >
         <div>
        <div id="entries">
<p></p>
        </div>
        </div>
            
           <div style="overflow-x: auto;text-align:center">
 
<h1>Forbidden</h1>
<p>you are not allowed to access this page</p>


            </div>
            
      
     
            
            
            
    </div>  
    
    </main>
      
    
             
    
    
    </body>
    </html>

