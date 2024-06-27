<?php
session_start();

// Check if the user is not logged in and redirect to the login page if they are not
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please wait</title>
    <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="dashy.css">
    <style>
        #wait{
    display: flex;
    flex-direction: column;
    align-items:center;
    justify-content: center;
    padding-top: 10%;
}
.circle {
width: 100px;
height: 100px;
border: 10px solid grey;
border-top-color: green;
border-radius: 50%;
animation: spin 2s linear infinite;
}


@keyframes spin {
0% {
transform: rotate(0deg);
}
100% {
transform: rotate(360deg);
}
}

    </style>
    <script>
        setTimeout(function(){
            window.location.href = "admin.php";
        }, 4000); 
    </script>
</head>
<body>
    <div id="container">
        <div id="wait">
            <p>Please wait..</p>
            <div class="circle"></div>
        </div>
    </div>
</body>
</html>
