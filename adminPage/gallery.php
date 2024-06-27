<?php
session_start();

// Check if admin is not logged in, then redirect to adminlogin.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: adminlogin.php');
    exit;
}

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $description = $_POST['description'];
        $category = $_POST['category'];
        $targetDir = "fileUploads/";
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow certain file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mpeg');

        if (in_array($fileType, $allowedTypes)) {
            // Upload file
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                // Insert data into database
                $sql = "INSERT INTO gallery (description, category, file, post_date) VALUES ('$description', '$category', '$targetFile', NOW())";

                if (mysqli_query($connection, $sql)) {
                    echo "Post created successfully";
                    header('Location: PR.php');
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "File type not allowed";
        }
    } else {
        echo "No file uploaded or there was an error uploading the file.";
    }
}

// Close the database connection
mysqli_close($connection);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <br>
    <a href="PR.php">Go Back</a>
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