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
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">

</head>
<body>
    <div id="container">
<div id="wait">
        <h3>Welcome</h3>
        <p>Please wait..</p>
        <div class="circle"></div>
    </div>
</div>

<div id="dashboard" style="display:none">
    <h2>Dashboard creation in progress</h2>
</div>








    <script>
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
</script>
</body>
</html>