<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = 'sdb-72.hosting.stackcp.net';
$username = "gudfama";
$password = "Nikido886@";
$database = "gudfama-35303631fafd";

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message

// Check if the token is provided in the URL
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($con, $_GET['token']);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newPassword = mysqli_real_escape_string($con, $_POST['new_password']);
        $confirmPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);

        // Check if new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            $errorMessage .= 'New password and confirm password do not match.';
        } else {
            // Verify the token and check if it has not expired
            $tokenQuery = "SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()";
            $stmt = $con->prepare($tokenQuery);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Hash the new password before storing it in the database
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
                $stmt = $con->prepare($updateQuery);
                $stmt->bind_param("ss", $hashedPassword, $email);

                if ($stmt->execute()) {
                    $successMessage = 'Password updated successfully.';
                    // Delete the used token
                    $deleteTokenQuery = "DELETE FROM password_resets WHERE email = ?";
                    $stmt = $con->prepare($deleteTokenQuery);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                } else {
                    $errorMessage .= 'Failed to update password.';
                }
            } else {
                $errorMessage .= 'Invalid or expired token.';
            }
        }
    }
} else {
    $errorMessage .= 'No token provided.';
}

$con->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="student.css">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        align-items: center;
        flex-direction: column;
    }
    #div {
        text-align: center;
        margin: 30px;
        padding: 20px;
        box-shadow: 1px 1px 10px rgb(197, 197, 197);
        border-radius: 10px;
    }
    form > input {
        padding: 10px;
        margin: 10px;
        border: 1px solid rgb(221, 221, 221);
        border-radius: 10px;
    }
    #submit {
        background-color: #2CB67D;
        color: white;
    }
    </style>
</head>
<body>

<main style="display: flex; align-items: center; justify-content: center;">
    <div style="overflow: auto; padding: 20px;" id="role">
        <div id="pass">
            <h2>Change your password</h2>
           
            <form action="" method="post">
                <label for="new_password">Enter new password</label><br>
                <input type="password" id="new_password" name="new_password" required><br>
                <label for="confirm_password">Confirm new password</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br>
                <span style="font-size:14px">Please enter a password you can remember!!</span><br>
                <input type="submit" id="submit" value="Change">
                <?php
                if ($errorMessage !== '') {
                    echo '<div style="color: red; text-align: left;">' . $errorMessage . '</div>';
                }

                if ($successMessage !== '') {
                    echo '<div style="color: green; text-align: left;">' . $successMessage . '</div>';
                }
                ?>
            </form>
        </div> 
    </div>
</main>

</body>
</html>
