<?php
session_start();
// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}
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

$id = $_SESSION['username'];
$sql = "SELECT * FROM jawaan_details WHERE jawaan_id='$id'";
$result = mysqli_query($conn, $sql);

//fetching data from jawaan_details table
if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    // Display user profile information
    $jawaan_name = $user['jawaan_name'];
    $jawaan_reg = $user['badge_no.'];
    $jawaan_post = $user['post'];
    $jawaan_img =$user['profile_img'];
    $jawaan_gen=$user['gender'];
    $jawaan_dob=$user['date_of_birth'];
    $jawaan_jy=$user['joining_year'];
    $jawaan_dep=$user['department'];
    $jawaan_stn=$user['station_name'];
    $jawaan_contact=$user['contact'];
    $jawaan_email=$user['email'];
    $jawaan_address=$user['address'];

}
 else {
    echo "User not found.";
}
// Check if logout button is clicked
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to homepage after logout
    header("location: homepage.php");
    exit; // Exit after redirect
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Details</title>
    <link rel="stylesheet" href="jawan_profile.css">
    <style>
          .logout-btn {
            background-color: #0056b3;
            color: white;
            padding: 2px 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
            /* Darker red on hover */
        }
 
        #logout,.log {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 10px;
            margin-right: 10px;
            justify-content: flex-end;

        }
    </style>
</head>


<body>

    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>

    <div class="log">
    <form id = "logout" method="GET">
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>
    </div>
    

    <div class="container">
        <header>
            <h1>Jawan Profile Details</h1>
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
                    <label for="regiment">Badge No.:</label>
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
