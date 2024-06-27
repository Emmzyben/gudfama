<?php
session_start(); // Start a session

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location:login.html"); // Replace 'login.php' with the actual login page URL
    exit(); // Make sure to exit after redirection
}

// Replace these with your database connection details
    $servername = 'localhost';
    $username = "adolphtr";
    $password = "Nikido886@";
    $database = "adolphtr_adolpht";
// Create a database connection
$conn = new mysqli($servername , $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all certificates ordered by registration_id
$sql = "SELECT * FROM certificate_registration ORDER BY registration_id";

// Execute the query
$result = $conn->query($sql);

// Check if there are any records
?>



<!DOCTYPE html>


        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="shortcut icon" href="images/logo.png">
                <title>Adolph.T Resources</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <script src="https://kit.fontawesome.com/f0fb58e769.js" crossorigin="anonymous"></script>
                <script src="https://cdn.tailwindcss.com"></script>
                <link rel="stylesheet" href="style.css">
                <style>
   
                    #defaultDiv {
                        display: block;
                        margin: 20px;
                        padding: 10px;
                        background-color: #382968;
                        font-size: 20px;
                        color: white;
                        font-weight: bolder;
                        text-align: center;
                    }
                
                    /* Define CSS styles for the certificates container */
                    .certificates-container {
                        display: flex;
                        flex-direction: row;
                        flex-wrap: wrap;
                        gap: 10px; /* Add some space between certificates */
                        margin-top: 10px;
                    }
                
                    /* Define CSS styles for each certificate */
                    .certificate-details {
                        padding: 10px;
                        border: 1px solid #382968;
                        margin-bottom: 10px;
                        box-sizing: border-box;
                        font-size: 13px;
                    }
                .certificate-image{
                    width: 70px;
                }
                    /* Define a media query for smaller screens */
                    @media (max-width: 768px) {
                        .certificate-details {
                            flex: 0 0 calc(100% - 10px); /* Full width for each certificate on smaller screens */
                        }
                    }
                
                
                           </style>
            </head>
            <body>
              <div id="sidebody"></div>
              <div id="middle">    
                <header>
                <div style="padding: 20px;padding-bottom: 0;">
                  <img src="images/logo.png" alt="">
                  <p style="color: purple;">   Adolph T. Resources Nigeria Limited</p>
                </div>
                    <div id="center"> 
                     <span >Welcome To Adolph T. Resources Nigeria Limited</span> 
                    <div style=" background-color: #c45c30;color: white;padding-top: 0.5px;"> 
                       <p>Commercial, Industrial & Domestic Scaffolding Services.</p> 
                   </div>
                   <div id="nav">
                      <div><a href="index.html">Home</a> </div>   
                      <div><a href="about.html">About Us</a></div>        
                      <div><a href="services.html">Our Products & Services</a> </div>
                      <div><a href="certificate-verification.html">Certificate Verification</a></div>  
                      <div><a href="contact.html">Contact Us</a> </div>                                                                            
                   </div>
                </div>
                
                    </header>   
                       
                        <aside> 
                          <div id="title" >
                            <span><img src="images/logo.png" alt="" ></span>
                            <span><p>Adolp T.Resources</p></span>
                        </div> 
                            <div onclick="openNav()" >
                                <div class="container1" onclick="myFunction(this)" id="sideNav">
                                    <div class="bar1"></div>
                                    <div class="bar2"></div>
                                    <div class="bar3"></div>
                                  </div>
                                </div>
                        </aside>
                
                
                          <div id="mySidenav" class="sidenav">
                            <img src="images/logo.png" alt="" width="70px" height="70px"><br>
                              <a href="index.html">Home</a>
                              <a href="about.html">About Us</a>
                              <a href="services.html">Our Products & Services</a> 
                              <a href="certificate-verification.html">Certificate Verification</a>
                              <a href="contact.html">Contact Us</a>
                          </div>
                
                      
        

    <div class="border-b border-gray-200 dark:border-gray-700" style="border-bottom: 1px solid #382968;" >
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            <li class="mr-2">
                <a href="#" onclick="handleNavClick('post')" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-[white] hover:border-[#382968ec] dark:hover:text-[#382968ec] group">
                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-[#382968ec]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                    </svg>Make a Post
                </a>
            </li>
            <li class="mr-2">
                <a href="#" onclick="handleNavClick('pictures')" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-[white] hover:border-[#382968ec] dark:hover:text-[#382968ec] group">
                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-[#382968ec]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                    </svg>Upload Pictures
                </a>
            </li>
            <li class="mr-2">
                <a href="#" onclick="handleNavClick('delete')" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-[white] hover:border-[#382968ec] dark:hover:text-[#382968ec] group">
                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-[#382968ec]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                    </svg>Delete pictures/post
                </a>
            </li>
            <li class="mr-2">
                <a href="#" onclick="handleNavClick('certificate')" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-[white] hover:border-[#382968ec] dark:hover:text-[#382968ec] group">
                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-[#382968ec]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z"/>
                    </svg>Upload Certificate
                </a>
            </li>
            <li class="mr-2">
                <a href="#" onclick="handleNavClick('default')" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-[white] hover:border-[#382968ec] dark:hover:text-[#382968ec] group">
                    <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-[#382968ec]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                    </svg>Student Database
                </a>
            </li>
            
        </ul>
    </div>
    <?php
        echo '<div id="defaultDiv">
    <div>
        <h1 class="header">Adolph T. Resources Nigeria Limited Student Database</h1>
        <div class="certificates-container">'; // Start certificates container

// Output data of each row
while ($row = $result->fetch_assoc()) {
    // Start a certificate container
    echo '<div class="certificate-details">';
    // Display the image if the image_path is not empty
    if (!empty($row["image_path"])) {
        echo '<img src="' . $row["image_path"] . '" alt="Certificate Image" class="certificate-image">';
    }
    echo "<p><strong>First Name:</strong> " . $row["first_name"] . "</p>";
    echo "<p><strong>Middle Name:</strong> " . $row["middle_name"] . "</p>";
    echo "<p><strong>Last Name:</strong> " . $row["last_name"] . "</p>";
    echo "<p><strong>Address:</strong> " . $row["address"] . "</p>";
    echo "<p><strong>City:</strong> " . $row["city"] . "</p>";
    echo "<p><strong>State:</strong> " . $row["state"] . "</p>";
    echo "<p><strong>Country:</strong> " . $row["country"] . "</p>";
    echo "<p><strong>Date of Issuance:</strong> " . $row["date_of_issuance"] . "</p>";
    echo "<p><strong>Phone Number 1:</strong> " . $row["phone_number1"] . "</p>";
    echo "<p><strong>Phone Number 2:</strong> " . $row["phone_number2"] . "</p>";
    echo "<p><strong>email:</strong> " . $row["email"] . "</p>";
    echo "<p><strong>Skill Learnt:</strong> " . $row["skill_learnt"] . "</p>";
    echo "<p><strong>Certificate number:</strong> " . $row["hash_field"] . "</p>";
    // End the certificate container
    echo '</div>';
}

echo '</div></div></div>'; // End certificates container and defaultDiv

// Close the database connection
$conn->close();
?>
<div id='deletediv' style="height: 400px;display:none" >
<form action="delete_picture.php" method="post" autocomplete="off" enctype="multipart/form-data">  <fieldset class="grid grid-cols-4 gap-6 p-6 rounded-md shadow-sm dark:bg-[#382968ec] text-[white]">
                    <div class="space-y-2 col-span-full lg:col-span-1">
                        <p class="font-medium">Delete a picture</p>
                        <p class="text-xs">to delete a picture, paste the picture description here</p>
                    </div>
                    <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                        <div class="col-span-full">
                            <label for="post" class="text-sm">Post description</label>
                            <textarea id="description" name="description" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900"></textarea>
                        </div>
                        <button class="text-[white] bg-[#382968ec] border-0 py-2 px-6 focus:outline-none hover:bg-[#fe5000] rounded text-lg" type="submit">Delete</button>
                    </div>
                </fieldset>
</form> <br>
<hr>
<form action="delete_post.php" method="post" autocomplete="off" enctype="multipart/form-data">  <fieldset class="grid grid-cols-4 gap-6 p-6 rounded-md shadow-sm dark:bg-[#382968ec] text-[white]">
                    <div class="space-y-2 col-span-full lg:col-span-1">
                        <p class="font-medium">Delete a post</p>
                        <p class="text-xs">To delete a post, paste the post title here</p>
                    </div>
                    <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                        <div class="col-span-full">
                            <label for="post" class="text-sm">Post title</label>
                            <textarea id="description" name="title" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900"></textarea>
                        </div>
                        <button class="text-[white] bg-[#382968ec] border-0 py-2 px-6 focus:outline-none hover:bg-[#fe5000] rounded text-lg" type="submit">Delete</button>
                    </div>
                </fieldset>
            </form>
</div>
    <div id="postDiv" style="display: none;">
        <section class="p-6 dark:bg-[#3829688b] dark:text-gray-50"> 
            <form action="post_upload.php" method="post" autocomplete="off" enctype="multipart/form-data" class="container flex flex-col mx-auto space-y-12">
                <fieldset class="grid grid-cols-4 gap-6 p-6 rounded-md shadow-sm dark:bg-[#382968ec]">
                    <div class="space-y-2 col-span-full lg:col-span-1">
                        <p class="font-medium">Blog</p>
                        <p class="text-xs">Make a blog post</p>
                    </div>
                    <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                        <div class="col-span-full sm:col-span-3">
                            <label for="post_title" class="text-sm">Post Title</label>
                            <input name="post_title" type="text" placeholder="Title" class="w-full rounded-md focus:ring focus:ring focus:ring dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full">
                            <label for="post_content" class="text-sm">Post Content</label>
                            <textarea name="post_content" placeholder="" class="w-full rounded-md focus:ring focus:ring focus:ring dark:border-gray-700 dark:text-gray-900"></textarea>
                        </div>
                        <div class="col-span-full">
                            <label for="file" class="text-sm">Attach a Photo (optional)</label>
                            <input type="file" name="file" class="w-full rounded-md focus:ring focus:ring focus:ring dark:border-gray-700 dark:text-gray-900"/>
                        </div>
                        <button type='submit' class="text-[white] bg-[#382968ec] border-0 py-2 px-6 focus:outline-none hover:bg-[#fe5000] rounded text-lg">Upload</button>
                    </div>
                </fieldset>
            </form>
            
        </section>
    </div>
    <div id="picturesDiv" style="display: none;">
        <section class="p-6 dark:bg-[#3829688b] dark:text-gray-50">
            <form action="image_upload.php" method="post" autocomplete="off" enctype="multipart/form-data">  <fieldset class="grid grid-cols-4 gap-6 p-6 rounded-md shadow-sm dark:bg-[#382968ec]">
                    <div class="space-y-2 col-span-full lg:col-span-1">
                        <p class="font-medium">Post a picture</p>
                        <p class="text-xs">upload a picture to the client area</p>
                    </div>
                    <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                        <div class="col-span-full">
                            <label for="post" class="text-sm">Description</label>
                            <textarea id="description" name="description" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900"></textarea>
                        </div>
                        <div class="col-span-full">
                            <label for="bio" class="text-sm">Upload Photo</label>
                            <input type="file" name="file" accept="image/*" required  class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <button class="text-[white] bg-[#382968ec] border-0 py-2 px-6 focus:outline-none hover:bg-[#fe5000] rounded text-lg" type="submit">Upload</button>
                    </div>
                </fieldset>
            </form>
            
        </section>
    </div>
    <div id="certificateDiv" style="display: none;">
        <section class="p-6 dark:bg-[#3829688b] dark:text-gray-50">
        <form method="POST" action="certificate.php" class="container flex flex-col mx-auto space-y-12" enctype="multipart/form-data">
                  <fieldset class="grid grid-cols-4 gap-6 p-6 rounded-md shadow-sm dark:bg-[#382968ec]">
                    <div class="space-y-2 col-span-full lg:col-span-1">
                        <p class="font-medium">Upload certificate Information</p>
                        <p class="text-xs">fill the form with the student certificate details</p>
                    </div>
                    <div class="grid grid-cols-6 gap-4 col-span-full lg:col-span-3">
                        <div class="col-span-full sm:col-span-3">
                            <label for="first_name" class="text-sm">First name</label>
                            <input id="firstname" name="first_name" type="text" placeholder="First name" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-3">
                            <label for="middle" class="text-sm">Middle name</label>
                            <input id="middle_name" type="text" name="middle_name" placeholder="Middle name" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-3">
                            <label for="lastname" class="text-sm">Last name</label>
                            <input id="lastname" type="text" name="last_name" placeholder="Last name" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-3">
                            <label for="email" class="text-sm">email</label>
                            <input id="email" type="text" name="email" placeholder="email" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full">
                            <label for="address" class="text-sm">Address</label>
                            <input id="address" type="text" name="address" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900"  />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="city" class="text-sm">City</label>
                            <input id="city" type="text" name="city" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="state" class="text-sm">State / Province</label>
                            <input id="state" type="text" placeholder="" name="state" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="country" class="text-sm">country</label>
                            <input id="country" type="text" placeholder="" name="country" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="date" class="text-sm">Date of issuance</label>
                            <input id="date" type="date" name="date_of_issuance" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="phone" class="text-sm">Phone number1</label>
                            <input id="phone_number1" type="number" name="phone_number1" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="phone" class="text-sm">Phone number2</label>
                            <input  id="phone_number2" type="number" name="phone_number2" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                        </div>
                        <div class="col-span-full sm:col-span-2">
                <label for="image" class="text-sm">Upload Certificate Image</label>
                <input id="image" type="file" name="image" accept="image/*" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                 </div>
                        <div class="col-span-full sm:col-span-2">
                            <label for="country" class="text-sm">Skill learnt</label>
                            <input id="skill_learnt" type="text" name="skill_learnt" placeholder="" class="w-full rounded-md focus:ring focus:ri focus:ri dark:border-gray-700 dark:text-gray-900" />
                            <br/><br/>
                            <button class="text-[white] bg-[#382968ec] border-0 py-2 px-6 focus:outline-none hover:bg-[#fe5000] rounded text-lg">Upload</button>
                        </div> 
                    </div>
                </fieldset>
            </form>
        </section>
    </div>

    <script>
        function handleNavClick(divName) {
            document.getElementById('defaultDiv').style.display = divName === 'default' ? 'block' : 'none';
            document.getElementById('postDiv').style.display = divName === 'post' ? 'block' : 'none';
            document.getElementById('picturesDiv').style.display = divName === 'pictures' ? 'block' : 'none';
            document.getElementById('certificateDiv').style.display = divName === 'certificate' ? 'block' : 'none';
            document.getElementById('deletediv').style.display = divName === 'delete' ? 'block' : 'none';
        }
    </script>

<footer>
    <div>
     <p>Copyright © 2024 Adolph T. Resources</p> 
     </div>
     <div><p><i class="fa fa-envelope" style="font-size:15px;color:#ffffff;padding-right: 10px;"></i>support@adolphtresources.com.ng </p></div>
      <div><p><i class="fa fa-phone" style="font-size:15px;color:#ffffff;padding-right: 10px;"></i> +234 (0) 810 238 7889, +234 (0) 812 487 4990</p>
       </div>
  </footer>

  <script src="index.js"></script>



  </div>
  <script>
let isMenuOpen = false;

const toggleMenu = () => {
    const menu = document.getElementById("ul");
    
    if (!isMenuOpen) {
        menu.style.height = "auto";
        isMenuOpen = true;
    } else {
        menu.style.height = "0px";
        isMenuOpen = false;
    }
};

const closeMenu = () => {
    const menu = document.getElementById("ul");
    menu.style.height = "0px";
    isMenuOpen = false;
};
</script>

</body>
</html>
