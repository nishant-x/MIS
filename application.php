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
$sql = "SELECT * FROM `jawaan_details` WHERE `jawaan_id`='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$badge = $row['badge_no.'];
$station_name = $row['station_name'];

//taking data from the html form of application
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $badge_no = $badge;
    $station = $station_name; // Correctly handle the selected station

    $date_from = $_POST["date_from"];
    $date_to = $_POST["date_to"];
    $reason = $_POST["reason"];

    $id = $_SESSION['username'];
    $sql = "INSERT INTO `apply_for_leave`(`jawaan_id`, `reason`, `badge_no.`, `station_name`, `date_from`, `date_to`) VALUES ('$id','$reason','$badge_no','$station','$date_from','$date_to')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<Script>
        alert('Application sent Successfully');
        window.location.href='jawan_home.php';
        </Script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
// Check if logout button is clicked
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to homepage after logout
    header("location: https://misportalbpl.000webhostapp.com/");
    exit; // Exit after redirect
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave application</title>
    <link rel="stylesheet" href="application.css">
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

    <form action="application.php" method="post">
        <h1>Application for leave of absence</h1>
        <div class="user_details">
            <h5>BADGE NUMBER:</h5>
            <input type="text" value=" <?php echo "$badge" ?> ">
            <h5>CHOOSE STATION</h5>
            <label for="station">
                <select name="station" id="station" required>
                <option value=''>Select station</option>
                    <?php
                        echo "<option value=''>$station_name</option>";
                    ?>

                </select>
            </label>

        </div>
        <div class="reason">
            <h5>DATE</h5>
            From <input type="date" placeholder="dd/mm/yyyy" name="date_from"> To
            <input type="date" placeholder="dd/mm/yyyy" name="date_to">
            <H5>Reason </H5>
            <textarea cols="" rows="12" placeholder="Reason of leave" name="reason"></textarea><br />
        </div>
        <div class="button">
            <input type="submit" class="submit" value="Submit">
        </div>
    </form>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>

</body>

</html>