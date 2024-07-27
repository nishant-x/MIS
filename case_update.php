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
$id=$_SESSION['username'];
// Ensure the user is logged in and get the user ID from the session
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in and the id is retrieved from the login page
    header("Location: login.php");
    exit(); // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $case_number = $_POST["case_number"];
    $sql = "SELECT * FROM case_study_of_jawaan WHERE case_number='$case_number' AND jawaan_id='$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        // Case number exists, set it in the session and redirect
        $_SESSION['case_number'] = $case_number;
        header("location: case_update_button.php");
        exit();
    } else {
       echo" <script>
       alert('Please enter a valid case number');
       window.location.href='jawan_home.php';
       </script>";
    }
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
    <link rel="stylesheet" href="addnew_case.css">
    <title>CASE REPORT</title>
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
    


    <div class="main_content">
        <div class="heading">
            <h2><b>UPDATE CASE REPORT</b></h2>
        </div>
        <!-- Input section of case number -->
        <section class="pre_details">
            <form method="POST">
                <label>Case Number:</label>
                <input type="text" placeholder="Enter Case Number" id="caseNumber" name="case_number">
                <button type="submit" class="update" id="incidentUpdateButton" name="update">Update</button>
            </form>
        </section>
        <footer>
            <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
        </footer>
    </div>
</body>

</html>
