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
$station = $_SESSION['username'];
// Ensure the user is logged in and get the user ID from the session
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in and the id is retrieved from the login page
    header("Location: login_station.php");
    exit(); // Stop further execution
}

$sql = "SELECT * FROM station_details WHERE station_id='$station'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$station_name = $row['station_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $case_number = $_POST["case_number"];
    $sql = "SELECT * FROM case_study_of_jawaan WHERE case_number='$case_number' AND station_name='$station_name'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Case number exists, set it in the session and redirect
        $_SESSION['case_number'] = $case_number;
        header("location: allotment_update_button.php");
        exit();
    } else {
        echo " <script>
       alert('Please enter a valid case number');
       window.location.href='station_home.php';
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
</head>
<style>
    /* Styles for the dropdown */
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* Styles for the form label */
    label {
        font-weight: bold;
    }

    .logout-btn {
        background-color: #0056b3;
        ;
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

    .logout {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

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

    .logout {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        justify-content: space-between;
    }
</style>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>
    <form class="logout" method="GET" class='logout'>

        <?php
        echo "<h3 style='color: darkblue;'>Station Name : $station_name</h3>";
        ?>

        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>

    <div class="main_content">
        <div class="heading">
            <h2><b>UPDATE ALLOTMENT</b></h2>
        </div>
        <!-- Input section of case number -->
        <!-- Input section of case number -->
        <section class="pre_details">
            <form method="POST">
                <label for="case_number">Case Number:</label>
                <select name="case_number" id="case_number" required> <!-- Changed name to "case_number" -->
                    <option value=''>Select Case Number</option>
                    <?php
                    // Fetch data from the database
                    // select case under station
                    $sql2 = "SELECT * FROM case_study_of_jawaan WHERE station_name='$station_name'";
                    $result2 = mysqli_query($conn, $sql2);

                    if (mysqli_num_rows($result2) > 0) { // Changed $result to $result2
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            echo "<option value='" . $row2['case_number'] . "'>" . $row2['case_number'] . "</option>";
                        }
                    } else {
                        echo "<option disabled>No Case Found</option>";
                    }

                    // Close connection
                    mysqli_close($conn);
                    ?>
                </select>
                <button type="submit" class="update" id="incidentUpdateButton" name="update">Update</button> <!-- Changed button type to "submit" -->
            </form>
        </section>

        <footer>
            <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
        </footer>
    </div>
</body>

</html>