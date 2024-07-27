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

$id = $_POST['station_name'];
$sql = "SELECT * FROM station_details WHERE station_name ='$id' ";
$result = mysqli_query($conn, $sql);

//fetching data from jawaan_details table
if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    // Display user profile information
    $station_name = $user['station_name'];
    $station_contactno = $user['contact_no.'];
    $station_address = $user['station_address'];
    $station_pincode = $user['pincode'];
    $station_officer_incharge = $user['officer_incharge'];
    $station_email = $user['station_email'];
    $station_stationtype = $user['station_type'];
} else {
    echo "User not found.";
}
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
            margin-bottom: 5px;
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
    <form id="logout" method="GET">
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>
    </div>

    <div class="container">
        <header>
            <h1>Police Station Profile</h1>
            <img src="logo.png" alt="Logo" height="100" width="100">
        </header>

        <main>
            <div class="profile-details">
                <div class="profile-item">
                    <label for="station">Station Name:</label>
                    <span id="station"><?php echo "$station_name"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="phone">Contact No.:</label>
                    <span id="phone"><?php echo "$station_contactno"; ?></span>
                </div>


                <div class="profile-item">
                    <label for="address">Location:</label>
                    <span id="address"><?php echo "$station_address"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="officer_in_charge">Officer InCharge:</label>
                    <span id="officer_in_charge"><?php echo "$station_officer_incharge"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="email">Email:</label>
                    <span id="email"><?php echo " $station_email"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="station_code">Pincode:</label>
                    <span id="station_code"><?php echo "$station_pincode"; ?></span>
                </div>

                <div class="profile-item">
                    <label for="station_type">Type of Station:</label>
                    <span id="station_type"><?php echo "$station_stationtype"; ?></span>
                </div>

            </div>
        </main>
    </div>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>