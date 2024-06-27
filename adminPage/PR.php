<?php
session_start();

// Check if admin is not logged in, then redirect to admin.php
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
checkAccess(['pr']); // Only allow 'pr' role to access this page

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $targetDir = "fileUploads/";
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mpeg');

    if (in_array($fileType, $allowedTypes)) {
        // Upload file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Insert data into database
            $sql = "INSERT INTO posts (title, content, author, category, file, post_date) VALUES ('$title', '$content', '$author', '$category', '$targetFile', NOW())";

            if (mysqli_query($connection, $sql)) {
                $successMessage = "Post created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($connection);
            }
        } else {
           $errorMessage = "Error uploading file.";
        }
    } else {
      $errorMessage = "File type not allowed";
    }
}
?>


<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="../images/logo.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
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
           <a href="accountsPage.php" >Accounts department</a>
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
                    <li ><a href="accountsPage.php" id="focus2">Accounts department</a></li>
                    <li ><a href="PR.php" id="focus1">PR department</a></li>
                    <li><a href="adminlogout.php" id="focus2">Logout</a></li>
                </ul>
            </div>  
    </div>
     
            
    <div style="padding: 20px;overflow:auto" id="main" >
         <div>
        <h2 style="color:white;background-color:#2CB67D ;padding:10px">Post Contents</h2>
        
        <div id="inner" >
  <div>
  <form action="" id="form1" method="post" enctype="multipart/form-data">
    <label for="title">Make a blog post or Announcement: </label><br>
    <input type="text" name="title"  placeholder="Post Title"><br>
    <input type="text" name="author"  placeholder="Post Author"><br>
    <select name="category" id="">
        <option value="">Category</option>
        <option value="blog">Blog post</option>
        <option value="Announcement">Announcement</option>
    </select><br>
    <textarea name="content"  cols="30" rows="10" placeholder="Write here"></textarea><br>
    <label for="image">Attach picture</label><br>
    <input type="file" id="file" name="file"><br>
    <button type="submit">Post to updates</button>
   
</form>

    </div>  


    

    <!-- gallery -->
    <div>
  <form action="gallery.php" id="form1" method="post" enctype="multipart/form-data">
    <label >Post picture or video to gallery pages: </label><br>
    <textarea name="description"  cols="30" rows="10" placeholder="Picture description" required></textarea><br>
    <select name="category" id="" required>
        <option value="">Category</option>
        <option value="picture">Picture</option>
        <option value="video">Video</option>
    </select><br>
    <label for="image">Attach picture or Video</label><br>
    <input type="file" id="file" name="file" required><br>
    <button type="submit">Post to gallery</button>
</form>
    </div>  

 

</div >
      
     
            
            
            
    </div>  
    
    </main>
      
    
             
    <div style="position:fixed;bottom:5%;right:5%">
    <?php
if (!empty($errorMessage)) {
    echo "<h3 style='color:red'>$errorMessage</h3>";
} elseif (!empty($successMessage)) {
    echo "<h3 style='color:green'>$successMessage</h3>";
}
?>
    </div>
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

