<?php
session_start();

// Check if admin is not logged in, then redirect to adminlogin.php
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

checkAccess(['accounts']); 

$servername = 'localhost';
$username = "root";
$password = "";
$database = "gudfama";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM users ORDER BY id DESC" ;
$result = mysqli_query($connection, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>



<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="../images/logo.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link rel="stylesheet" href="admin.css">
        <style>
            #inmain>div{
                box-shadow: 1px 1px 10px rgb(219, 218, 218);
                padding: 10px;
                margin-bottom: 10px;
            }
            #inmain input{
                padding: 7px;border: 1px solid rgb(219, 218, 218);
            }
           #submit{
            background-color: #2CB67D;border: none;color: white;font-weight: 700;
           } 
           #form{
            display: flex;flex-direction: row;flex-wrap: wrap;
           }
           #form label{
            font-size: 13px;
           }
           #form>div{
            padding: 10px;background-color:rgb(219, 218, 218);margin: 7px;
           }
       
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid rgb(219, 218, 218);
        }
        th, td {
            padding: 7px;
            text-align: left;font-size: 13px;
        }
        th {
            background-color: #f2f2f2;
        }
        @media screen and (max-width:700px) {
          body{
            overflow: auto;
        }  
        }
        
    </style>
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
                    <li ><a href="accountspage.php" id="focus1">Accounts department</a></li>
                    <li ><a href="PR.php" id="focus2">PR department</a></li>
                    <li><a href="adminlogout.php" id="focus2">Logout</a></li>
                </ul>
            </div>  
    </div>
     
            
    <div style="padding: 20px;overflow:auto" id="main" >
    <div id="inmain">
        <div>
        <form id="searchForm" action="search_results.php" method="GET">
        <input type="search" name="name" id="name" placeholder="Search Name">
        <input type="submit" id="submit" value="Search">
    </form>
        </div>

        <div>
       
    <script>
        function clearSelections() {
            // Get all checkboxes in the form
            var checkboxes = document.querySelectorAll('#form input[type="checkbox"]');
            // Iterate through the checkboxes and uncheck them
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        function validateForm() {
            var checkboxes = document.querySelectorAll('#form input[type="checkbox"]:checked');
            if (checkboxes.length === 0) {
                alert('Please select at least one field.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <form id="form" action="process_form.php" method="POST" onsubmit="return validateForm();">
        <div>
            <label for="name">Name</label>
            <input type="checkbox" name="fields[]" value="full_name" id="name">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="checkbox" name="fields[]" value="email" id="email">
        </div>
        <div>
            <label for="phone">Phone</label>
            <input type="checkbox" name="fields[]" value="phone" id="phone">
        </div>
        <div>
            <label for="account_number">Account Number</label>
            <input type="checkbox" name="fields[]" value="accountNumber" id="account_number">
        </div>
        <div>
            <input type="submit" id="submit" value="Submit">
            <input type="button" id="clear" value="Clear Selections" onclick="clearSelections()">
        </div>
    </form>


        </div>
    </div>
         
            
           <div style="overflow: auto;">
           <h2>Subscribers List</h2>

           <table>
    <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Subscribed Before?</th>
        <th>Date of Birth</th>
        <th>State of Residence</th>
        <th>How Did You Hear About Us</th>
        <th>Registration Date</th>
        <th>Account Name</th>
        <th>Bank Name</th>
        <th>Account Number</th>
        <th>Package</th>
        <th>Payment Plan</th>
        <th>Total Cost</th>
        <th>Amount Paid</th>
        <th>Amount To Pay</th>
        <th>Referral Link</th>
        <th>Receipt</th>
        <th>Payment Date</th>
        <th>Terms Accepted</th>
        <th>Confirm Payment</th>
        <th>Batch</th>
        <th>Receipt list for recurring payments</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
        echo "<td>" . htmlspecialchars($row['subscription']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
        echo "<td>" . htmlspecialchars($row['state_of_residence']) . "</td>";
        echo "<td>" . htmlspecialchars($row['how_did_you_hear_about_us']) . "</td>";
        echo "<td>" . htmlspecialchars($row['registration_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['accountName']) . "</td>";
        echo "<td>" . htmlspecialchars($row['bankName']) . "</td>";
        echo "<td>" . htmlspecialchars($row['accountNumber']) . "</td>";
        echo "<td>" . htmlspecialchars($row['package']) . "</td>";
        echo "<td>" . htmlspecialchars($row['paymentPlan']) . "</td>";
        echo "<td>" . htmlspecialchars($row['totalCost']) . "</td>";
        echo "<td>" . htmlspecialchars($row['amountPaid']) . "</td>";
        echo "<td>" . htmlspecialchars($row['amountToPay']) . "</td>";
        echo "<td>" . htmlspecialchars($row['referralLink']) . "</td>";
        echo "<td><a href='" . htmlspecialchars($row['receipt']) . "' target='_blank'>View Receipt</a></td>";
        echo "<td>" . htmlspecialchars($row['payment_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['terms_accepted']) . "</td>";
        echo "<td>";
        echo "<select name='confirmation[{$row['email']}]' onchange='updateConfirmation(\"{$row['email']}\", this)'>";
        echo "<option value='unconfirmed'" . ($row['confirmation'] == 'unconfirmed' ? ' selected' : '') . ">Unconfirmed</option>";
        echo "<option value='confirmed'" . ($row['confirmation'] == 'confirmed' ? ' selected' : '') . ">Confirmed</option>";
        echo "<option value='unsuccessful'" . ($row['confirmation'] == 'unsuccessful' ? ' selected' : '') . ">Unsuccessful</option>";
        echo "</select>";
        echo "</td>";
        echo "<td>" . htmlspecialchars($row['batch']) . "</td>";
        echo "<td><a href='receiptsPage.php?email=" . htmlspecialchars($row['email']) . "'>click</a></td>";
        echo "</tr>";
    }
    mysqli_free_result($result);
    ?>
</table>

<script>
function updateConfirmation(email, selectElement) {
    var confirmation = selectElement.value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_confirmation.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
        }
    };
    xhr.send("email=" + encodeURIComponent(email) + "&confirmation=" + encodeURIComponent(confirmation));
}
</script>






            </div>
            
      
     
            
            
            
    </div>  
    
    </main>
      
    
             
    
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

