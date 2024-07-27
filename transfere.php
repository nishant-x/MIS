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

$showError = false;
//taking data from the html form of application
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["search"])) {
        $badge = $_POST["badge"];
        $sql = "SELECT * FROM `jawaan_details` WHERE `badge_no.` = '$badge'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // Case number exists, set it in the session and redirect
            $_SESSION['badge_no.'] = $badge;
            header("location: transferesearch.php");
            exit();
        } else {
            $showError = "<span style='color: red;'>Invalid Badge Number</span>";
        }
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

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawan Transfer Order Application</title>
    <link rel="stylesheet" href="transfere.css">
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
    <form id="logout" method="GET">
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>
    </div>

    <form action="transfere.php" method="post" id="transferbadge">
        <h1>Jawan Transfer Order</h1>

        <div class="user_details">

            <h5>ENTER JAWAN BADGE NUMBER</h5>
            <?php if ($showError) : ?>
                <div class="error-message"><?php echo $showError; ?></div>
            <?php endif; ?>
            <input type="text" placeholder="Badge number" name="badge" required>
            <!-- button to search jawaan -->
            <div class="button2">
                <input type="submit" class="search" value="search" name="search">
            </div>
        </div>
    </form>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
    </footer>

</body>

</html>