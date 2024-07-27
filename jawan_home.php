<?php
// Start the session
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

// Fetch user details from jawaan_login table
$id = $_SESSION['username'];
$sql_login = "SELECT * FROM jawaan_login WHERE jawaan_id='$id'";
$result_login = mysqli_query($conn, $sql_login);

if (mysqli_num_rows($result_login) == 1) {
    $user = mysqli_fetch_assoc($result_login);
    // Assign user's jawaan_id
    $jawaan_id = $user['jawaan_id'];
} else {
    echo "User not found.";
}

// AT Transfere order date station name change
$query = "SELECT * FROM jawaan_transfer_orders WHERE jawaan_id='$id' AND joining_status ='pending...'";
$result1 = mysqli_query($conn, $query);
$row1 = mysqli_fetch_assoc($result1);
if($row1 > 0) {
$order_date = $row1['order_date'];
$joining = $row1['joining_date'];
$new_station = $row1['forstation'];
$cur_date = date("Y-m-d");


// Check if today is the joining date specified in the transfer order
if ($cur_date == $joining) {
    // Update the station name in jawaan_details table
    
    $sql_update_station = "UPDATE jawaan_details SET station_name='$new_station' WHERE  jawaan_id = '$jawaan_id'";
    $result_update_station = mysqli_query($conn, $sql_update_station);
    $sql_update_station2="UPDATE jawaan_transfer_orders SET `joining_status'='joined'  WHERE jawaan_id ='$jawaan_id' AND joining_status ='pending...' ";
    $result_update_station2 = mysqli_query($conn, $sql_update_station2);
    if (!$result_update_station) {
        echo "Error updating station: " . mysqli_error($conn);
    }
}
}

// Fetch user details from jawaan_details table
$sql_details = "SELECT * FROM jawaan_details WHERE jawaan_id='$id'";
$result_details = mysqli_query($conn, $sql_details);

if (mysqli_num_rows($result_details) == 1) {
    $user_details = mysqli_fetch_assoc($result_details);
    // Assign user details
    $jawaan_name = $user_details['jawaan_name'];
    $jawaan_reg = $user_details['badge_no.'];
    $jawaan_post = $user_details['post'];
    $jawaan_img = $user_details['profile_img'];
    $status = $user_details['jawaan_status'];
    $jawaan_email = $user_details['email'];
} else {
    echo "User details not found.";
}
// Fetch leave details of the jawan
$sql_leave = "SELECT * FROM apply_for_leave WHERE jawaan_id='$id' AND status = 'Approved'";
$result_leave = mysqli_query($conn, $sql_leave);

if (mysqli_num_rows($result_leave) > 0) {
    while ($row = mysqli_fetch_assoc($result_leave)) {
        $start = $row['date_from'];
        $end = $row['date_to'];
        $cur_date = date("Y-m-d");

        // Check if the leave start date is today or earlier and the end date is today or later
        if ($cur_date >= $start && $cur_date <= $end) {
            // Update jawan status to inactive when leave of jawan is started
            $sql_update_status = "UPDATE jawaan_details SET jawaan_status = 'unactive' WHERE jawaan_id = '$id'";
            if (mysqli_query($conn, $sql_update_status)) {
                $status = 'unactive'; // Update the status variable as well
            } else {
                echo "Error updating jawan status: " . mysqli_error($conn);
            }
            break; // exit the loop as we only need to update once
        }
    }
}

if (isset($_POST["active"])) {
    if ($status == 'active')
        echo '';
    if ($status == 'active-unavailable')
        echo '';
    if ($status == 'unactive')
     echo '';
}

// Handle form submission
if (isset($_POST["submit"])) {
    if (isset($_POST["active"])) {
        $status = $_POST['active'];

        $query = "UPDATE jawaan_details SET jawaan_status = '$status' WHERE jawaan_id = '$id'";
        $query_run = mysqli_query($conn, $query);

        if (!$query_run) {
            echo 'Failed: ' . mysqli_error($conn);
        } else {
            echo '';
        }
    } else {
        echo '';
    }
    $active_checked = isset($_POST['active']) ? $_POST['active'] : '';
}

// Logout logic
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to homepage after logout
    header("location:homepage.php");
    exit;
}

//TOTAL CASE UNDER JAWAN
$sql1 = "SELECT * FROM case_study_of_jawaan WHERE jawaan_id = '$id'";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_num_rows($result1);
//COMPLETE CASE UNDER JAWAN
$sql2 = "SELECT * FROM case_study_of_jawaan WHERE jawaan_id = '$id' and case_status='case-completed'";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_num_rows($result2);
//IN-COMPLETE CASE UNDER JAWAN
$sql3 = "SELECT * FROM case_study_of_jawaan WHERE jawaan_id = '$id' and case_status='case-active'";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_num_rows($result3);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawan Home</title>
    <link rel="stylesheet" href="jawan_home.css">
</head>

<body>
    <!-- Header -->
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>

    <!-- Main Container -->
    <div class="main_container">
        <!-- Navbar -->
        <div class="navbar">
            <a href="jawan_profile.php">User Profile</a>
            <a href="achievements.php">Achievements</a>
            <a href="case_update.php">UPDATE CASES</a>
            <a href="addnew_case.php">ADD NEW CASE</a>
            <a href="#active-status">Active Status</a>
            <a href="#order-panel">Order Panel</a>
            <a href="application.php">Leave Application</a>
            <a href="leave_records.php">Leave Records</a>
            <a href="?logout=true">Logout</a>
        </div>

        <!-- Jawan Details -->
        <div class="jawan_details">
            <div class="personal" id="user-profile">
                <div class="img">
                    <!-- Display user profile image -->
                    <?php
                    if ($jawaan_img) {
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($jawaan_img) . '" />';
                    }
                    ?>
                </div>
                <div class="info">
                    <!-- Display user details -->
                    <h3 class="name">NAME: <span class="jawan_name"><?php echo $jawaan_name; ?></span></h3>
                    <h3 class="id">JAWAN ID: <span class="jawan_id"><?php echo $jawaan_id; ?></span></h3>
                    <h3 class="regement_no">BADGE NO.: <span class="jawan_reg"><?php echo $jawaan_reg; ?></span></h3>
                    <h3 class="post">POST: <span class="jawan_post"><?php echo $jawaan_post; ?></span></h3>
                </div>
            </div>

            <!-- Achievement Section -->
            <div class="achievement" id="achievements">
                <h2>ACHIEVEMENT</h2>
                <!-- Display user achievements -->
                <?php
                $sql_achievement = "SELECT * FROM achievement_of_jawaan WHERE jawaan_id='$jawaan_id'";
                $result_achievement = mysqli_query($conn, $sql_achievement);

                if (mysqli_num_rows($result_achievement) > 0) {
                    echo "<div class='past_achievements' id='past_achievements'>
                            <table id='achievementTable'>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Certificate</th>
                                    </tr>
                                </thead>
                                <tbody id='achievementList'>";

                    while ($row = mysqli_fetch_assoc($result_achievement)) {
                        echo "<tr>
                                <td>" . $row['title'] . "</td>
                                <td>" . $row['date'] . "</td>
                                <td><a href='uploads/" . $row['achievement_file'] . "' target='_blank'>View Certificate</a></td>
                              </tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "No achievements found.";
                }
                ?>
            </div>

            <!-- Case Section -->
            <div class="case" id="case">
                <h2>Case Under Jawan</h2>
                <!-- Display user's case details -->
                <div class="cases_details">
                    <div class="total_case cases">Total Cases <span><?php echo "$row1"; ?></span></div>
                    <div class="complete_case cases">Completed Cases <span><?php echo "$row2"; ?></span></div>
                    <div class="incomplete_case cases">Incomplete Cases <span><?php echo "$row3"; ?></span></div>
                </div>
            </div>

            <!-- Active Status Section -->
            <div class="active_status" id="active-status">
                <h2>ACTIVE STATUS</h2>
                <form action="" method="post">
                    <input type="radio" name="active" id="active" value="active" <?php if ($status == 'active') echo 'checked'; ?>>
                    <label for="active">ACTIVE AND AVAILABLE IN STATION</label>
                    <input type="radio" name="active" id="active-unavailable" value="active-unavailable" <?php if ($status == 'active-unavailable') echo 'checked'; ?>>
                    <label for="active-unavailable">ACTIVE AND UNAVAILABLE IN STATION</label>
                    <input type="radio" name="active" id="unactive" value="unactive" <?php if ($status == 'unactive') echo 'checked'; ?>>
                    <label for="unactive">INACTIVE</label>
                    <div class="order_panel" id="order-panel">
                        <div class="order_message">
                            <button name="submit">Submit</button>
                        </div>
                    </div>
            </div>
            <!-- Order Panel Section -->
            <div class="order_panel" id="order-panel">
                <h2>ORDER PANEL</h2>
                <div class="order_message">
                    <p>Check your email to get the order details.</p>
                    <button onclick="window.location.href='mailto:email@example.com';">Check Order</button>
                </div>
            </div>

            <!-- Leave Application Section -->
            <div class="leave_application" id="leave-application">
                <h2>LEAVE APPLICATION</h2>
                <div class="application_status">
                    <!-- Display leave applications -->
                    <?php
                    $status = 'pending...';
                    $sql_leave = "SELECT * FROM apply_for_leave WHERE status='$status' AND jawaan_id='$id'";
                    $result_leave = mysqli_query($conn, $sql_leave);

                    if (mysqli_num_rows($result_leave) > 0) {
                        echo "<table border='1'>
                                <thead>
                                    <tr>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>APPLICATION ID</th>
                                        <th>TYPE OF LEAVE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>";

                        while ($row = mysqli_fetch_assoc($result_leave)) {
                            echo "<tr>
                                    <td>" . $row['date_from'] . "</td>
                                    <td>" . $row['date_to'] . "</td>
                                    <td>" . $row['jawaan_id'] . "</td>
                                    <td>" . $row['reason'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "No leave applications found.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>