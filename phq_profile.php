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
$sql = "SELECT * FROM phq_details WHERE phq_id='$id'";
$result = mysqli_query($conn, $sql);

//fetching data from jawaan_details table
if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    // Display user profile information
    $phq_id = $user['phq_id'];         
    $phq_contactno = $user['phq_contact'];
    $phq_address = $user['phq_address'];
    $phq_pincode=$user['pincode'];
    $phq_officer_incharge=$user['officer_incharge'];
    $phq_email=$user['phq_email'];
  

}
 else {
    echo "User not found.";
}

// Check if logout button is clicked
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to homepage after logout
    header("location:homepage.php");
    exit; // Exit after redirect
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Station Profile</title>
    <link rel="stylesheet" href="station_profile.css">
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
            <h1>Police Head Quarter Profile</h1>
            <img src="logo.png" alt="Logo" height="100" width="100">
        </header>

        <main>
            <div class="profile-details">
                <div class="profile-item">
                    <label for="station">PHQ ID:</label>
                    <span id="station"><?php echo"$phq_id"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="phone">Contact No.:</label>
                    <span id="phone"><?php echo"$phq_contactno"; ?></span>
                </div>


                <div class="profile-item">
                    <label for="address">Location:</label>
                    <span id="address"><?php echo"$phq_address"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="officer_in_charge">Officer In Charge:</label>
                    <span id="officer_in_charge"><?php echo"$phq_officer_incharge"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="email">Email:</label>
                    <span id="email"><?php echo"$phq_email"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="station_code">Pincode:</label>
                    <span id="station_code"><?php echo"$phq_pincode"; ?></span>
                </div>

               
            </div>
        </main>
    </div>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>
