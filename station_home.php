<?php
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login_station.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "mis";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_SESSION['username'];
$sql = "SELECT * FROM station_login WHERE station_id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
     $station_id = $user['station_id'];
} else {
    echo "User not found.";
}
$sql = "SELECT * FROM station_details WHERE station_id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $station_name = $user['station_name'];
    $station_address = $user['station_address'];
    $station_pincode = $user['pincode'];
    $station_pincode = $user['pincode'];
    $station_image = $user['station_image'];
    
} else {
    echo "User not found.";
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("location:homepage.php");
    exit;
}

?>

<?php

//php mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
            
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
//Action Of Approve and reject button 
if (isset($_POST["approve"])) {
    $approved = $_POST['approve'];
    $status = "Approved";
    $sql2 = "SELECT * FROM apply_for_leave WHERE status='pending...'";
    $res2 = mysqli_query($conn, $sql2);
    while ($row2 = mysqli_fetch_assoc($res2)) {
        $jawaan_id = $row2['jawaan_id'];
        if ($approved == 1) {
            //updating the status of leave request to approved in database
            $query = "UPDATE apply_for_leave SET `status`= '$status' WHERE jawaan_id = '$jawaan_id' AND status = 'pending...'";
            $query_run = mysqli_query($conn, $query);
            if ($query_run) {
                // Retrieve Jawan's email from the database
                $jawaan_email_query = "SELECT email FROM jawaan_details WHERE jawaan_id = '$jawaan_id'";
                $jawaan_email_result = mysqli_query($conn, $jawaan_email_query);
                $jawaan_email_row = mysqli_fetch_assoc($jawaan_email_result);
                $jawaan_email = $jawaan_email_row['email'];

                
                // Send mail to the Jawan who applied for leave
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'station.bhopal04@gmail.com'; // Enter your Gmail email address
                $mail->Password = 'avrd qdcw dpim dqad'; // Enter your Gmail password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                // Other mail settings...

                $mail->setFrom('station.bhopal04@gmail.com');
                $mail->addAddress($jawaan_email);

                // Mail subject and body based on status
                $subject = "*LEAVE APPROVAL*";
                $message = "Your leave request has been approved. We hope to see you soon.";

                $mail->Subject = $subject;
                $mail->Body = $message;

                if ($mail->send()) {
                    echo "<script>
                alert('Leave request approved successfully');
                window.location.href='station_home.php';
                </script>";
                } else {
                    echo "<script>
                alert('Failed to send approval email! Please try again later.');
                window.location.href='station_home.php';
                </script>";
                }
            }
        }
    }
}

// Reject button action
if (isset($_POST["reject"])) {
    $rejected = $_POST['reject'];
    $status = "Rejected";
    $sql2 = "SELECT * FROM apply_for_leave WHERE status='pending...'";
    $res2 = mysqli_query($conn, $sql2);
    while ($row2 = mysqli_fetch_assoc($res2)) {
        $jawaan_id = $row2['jawaan_id'];
        if ($rejected == 2) {
            // Updating the status of leave request to rejected in database
            $query = "UPDATE apply_for_leave SET `status`= '$status' WHERE jawaan_id = '$jawaan_id' AND status = 'pending...'";
            $query_run = mysqli_query($conn, $query);
            if ($query_run) {
                // Retrieve Jawan's email from the database
                $jawaan_email_query = "SELECT email FROM jawaan_details WHERE jawaan_id = '$jawaan_id'";
                $jawaan_email_result = mysqli_query($conn, $jawaan_email_query);
                $jawaan_email_row = mysqli_fetch_assoc($jawaan_email_result);
                $jawaan_email = $jawaan_email_row['email'];

                // Send mail to the Jawan who applied for leave
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'station.bhopal04@gmail.com'; // Enter your Gmail email address
                $mail->Password = 'avrd qdcw dpim dqad'; // Enter your Gmail password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                // Other mail settings...x

                $mail->setFrom('station.bhopal04@gmail.com');
                $mail->addAddress($jawaan_email);

                // Mail subject and body based on status
                $subject = "*LEAVE REJECTION*";
                $message = "Your leave request has been rejected. Please contact your superior for further details.";

                $mail->Subject = $subject;
                $mail->Body = $message;

                if ($mail->send()) {
                    echo "<script>
                alert('Leave request denied ');
                window.location.href='station_home.php';
                </script>";
                } else {
                    echo "<script>
                alert('Failed to send denied email! Please try again later.');
                window.location.href='station_home.php';
                </script>";
                }
            }
        }
    }
}
//TOTAL CASE UNDER STATION
$sql1 = "SELECT * FROM case_study_of_jawaan WHERE station_name='$station_name'";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_num_rows($result1);
//COMPLETE CASE UNDER STATION
$sql2 = "SELECT * FROM case_study_of_jawaan WHERE station_name='$station_name' and case_status = 'case-completed'";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_num_rows($result2);
//IN-COMPLETE CASE UNDER STATION
$sql3 = "SELECT * FROM case_study_of_jawaan WHERE station_name='$station_name' and case_status= 'case-active'";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_num_rows($result3);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Profile</title>
    <link rel="stylesheet" href="station_home.css">
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>
    <div class="main_container">
        <div class="nav_bar">
            <a href="station_profile.php">Station Profile</a>
            <a href="case_list_station.php">Case Details</a>
             <!--<a href="view_case.php">Case Details</a>-->
            <a href="allot_case.php">Allot Case</a>
            <a href="orderpanelforstation.php">Order Panel</a>
            <a href="#leave_approval">Leave Approval</a>
            <a href="jawan_list_station.php">Jawans List</a>
            <a href="station_list.php">Other Station details</a>
            <a href="?logout=true"> Logout </a>
        </div>
        <div class="station_details">
            <div class="station_profile" id="station_profile">
                <div class="img">
                    <?php
                    $result = $conn->query($sql);
                    if ($station_image) {
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($station_image) . '" />';
                    }
                    ?>
                </div>
                <div class="info">
                    <h3 class="name">Name: <span class="jawan_name"><?php echo "$station_name"; ?></span></h3>
                    <h3 class="id">Station id:<span class="jawan_id"><?php echo "$station_id"; ?></span></h3>
                    <h3 class="regement_no">Location: <span class="jawan_reg"><?php echo "$station_address"; ?></span></h3>
                    <h3 class="post">Pincode: <span class="jawan_post"><?php echo "$station_pincode"; ?></span></h3>
                </div>
            </div>
            <div class="station_case" id="station_case">
                <h2>Case in <?php echo"$station_name";?></h2>
               <!-- Display station's case details -->
               <div class="cases_details">
                    <div class="total_case cases">Total Cases <span><?php echo "$row1"; ?></span></div>
                    <div class="complete_case cases">Completed Cases <span><?php echo "$row2"; ?></span></div>
                    <div class="incomplete_case cases">Incomplete Cases <span><?php echo "$row3"; ?></span></div>
                </div>
            </div>
            <div class="station_order" id="station_order">
                <h2>Order Panel</h2>
                <div class="order_message">
                    <p>Send an order request to jawaan & station.</p>
                    <button onclick="window.location.href='orderpanelforstation.php';">Send Order</a></button>
                    <!-- <br><br> -->
                    <p>Check your email to get the order details.</p>
                    <button onclick="window.location.href='mailto:email@example.com';">Check Order</button>

                </div>
            </div>
            <div class="leave_approval" id="leave_approval">
                <h2>Leave Approvals</h2>
                <div class="application_status">
                    <?php
                    $status = 'pending...';
                    $sql = "SELECT * FROM apply_for_leave WHERE status='$status'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table border='1'>
                            <thead>
                                <tr>
                                    <th>JAWAAN NAME</th>
                                    <th>BADGE NO.</th>
                                    <th>START DATE</th>
                                    <th>END DATE</th>
                                    <th>TYPE OF LEAVE</th>
                                    <th colspan='3'>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            $jawaan_id = $row['jawaan_id'];
                            $sql2 = "SELECT * FROM jawaan_details WHERE jawaan_id='$jawaan_id'";
                            $result2 = mysqli_query($conn, $sql2);

                            if ($row2 = mysqli_fetch_assoc($result2)) {
                                $post = $row2['post'];
                                $badge = $row2['badge_no.'];
                                $station = $row['station_name'];

                                if ($post !== 'Town Inspector' && $post !== 'Cyber Crime Inspector' && $station === $station_name) {
                                    echo "<tr>
                    <td>" . $row2['jawaan_name'] . "</td>
                    <td>" . $row['badge_no.'] . "</td>
                    <td>" . $row['date_from'] . "</td>
                    <td>" . $row['date_to'] . "</td>
                    <td>" . $row['reason'] . "</td>
                    <td>
                        <form method='post'><button class='Approval_btn' name='approve' value='1'>Approve</button></form>
                        <form method='post'><button class='Rejected_btn' name='reject' value='2'>Deny</button></form>
                    </td>
                </tr>";
                                }
                            }
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

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>