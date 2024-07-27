<?php

session_start();
 
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "mis";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Use session variable

$name = $_POST['jawaan_name'];
$sql="SELECT * FROM jawaan_details WHERE jawaan_name= '$name'";
$result=mysqli_query($conn,$sql);


// Fetching data from jawaan_details table
if (mysqli_num_rows($result) == 1) {
    // $user = mysqli_fetch_assoc($result);
    $user = mysqli_fetch_array($result);
    // Display user profile information
    $jawaan_name = $user['jawaan_name'];
    $jawaan_reg = $user['badge_no.'];
    $jawaan_post = $user['post'];
    $jawaan_img = $user['profile_img'];
    $jawaan_gen = $user['gender'];
    $jawaan_dob = $user['date_of_birth'];
    $jawaan_jy = $user['joining_year'];
    $jawaan_dep = $user['department'];
    $jawaan_stn = $user['station_name'];
    $jawaan_contact = $user['contact'];
    $jawaan_email = $user['email'];
    $jawaan_address = $user['address'];
} else {
    echo "User not found.";
}

// Close the statement
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Details</title>
    <link rel="stylesheet" href="jawan_profile.css">
</head>


<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>
    <div class="container">
        <header>
            <h1>User Profile Details</h1>
            <img src="logo.png" alt="Logo" height="100" width="100">
        </header>

        <main>
            <div class="profile-details">
                
                <div class="profile-item">
                    <label for="name">Name:</label>
                    <span id="name"><?php echo "$jawaan_name";?></span>
                </div>

                <div class="profile-item">
                    <label for="gender">Gender:</label>
                    <span id="gender"><?php echo "$jawaan_gen"?></span>
                </div>

                <div class="profile-item">
                    <label for="dob">Date of Birth:</label>
                    <span id="dob"><?php echo "$jawaan_dob"?></span>
                </div>

                <div class="profile-item">
                    <label for="station">Station Name:</label>
                    <span id="station"><?php echo "$jawaan_stn";?></span>
                </div>

                <div class="profile-item">
                    <label for="regiment">BADGE No.:</label>
                    <span id="regiment"><?php echo "$jawaan_reg";?></span>
                </div>

                <div class="profile-item">
                    <label for="joiningYear">Joining Year:</label>
                    <span id="joiningYear"><?php echo "$jawaan_jy";?></span>
                </div>

                <div class="profile-item">
                    <label for="department">Department:</label>
                    <span id="department"><?php  echo "$jawaan_dep";?></span>
                </div>

                <div class="profile-item">
                    <label for="post">Post:</label>
                    <span id="post"><?php echo "$jawaan_post";?></span>
                </div>

                <div class="profile-item">
                    <label for="mobile">Mobile no.:</label>
                    <span id="mobile"><?php echo "$jawaan_contact";?></span>
                </div>

                <div class="profile-item">
                    <label for="email">Email ID:</label>
                    <span id="email"><?php echo "$jawaan_email";?></span>
                </div>

                <div class="profile-item">
                    <label for="address">Address:</label>
                    <span id="address"><?php echo "$jawaan_address";?></span>
                </div>
            </div>
        </main>
    </div>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>
