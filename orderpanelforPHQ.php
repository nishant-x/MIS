<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'phq.bhopal04@gmail.com'; // Enter sender Gmail address
    $mail->Password = 'gmfr myde cfif lrjm'; // Enter sender Gmail password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('phq.bhopal04@gmail.com'); //Sender email

    $selectedStations = $_POST["email"];

    foreach ($selectedStations as $email) {
        $mail->addAddress($email); //Receiver email
    }

    $mail->isHTML(true);
    $subject = "*EMERGENCY ORDER*";
    $message = "Reason: " . $_POST["reason"] . "<br>";
    $message .= "Male Required: " . $_POST["male_required"] . "<br>";
    $message .= "Female Required: " . $_POST["female_required"] . "<br>";
    $message .= "Location: " . $_POST["location"] . "<br>";

    $mail->Subject = $subject;
    $mail->Body = $message;

    if ($mail->send()) {
        echo "<script>alert('Order Sent Successfully');
        window.location.href='phq_home.php';
        </script>";
    } else {
        echo "<script>alert('Error: Unable to send Order.');
        window.location.href='phq_home.php';
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
    <title>Order Panel for PHQ</title>
    <link rel="stylesheet" href="orderpanelforPHQ.css">
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
        <img src="3logo3.png" alt="Logo">
    </nav>
    <div class="log">
    <form id = "logout" method="GET">
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>
    </div>

    <header>
        <h1>ORDER PANEL</h1>
    </header>

    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="sender-info">
                <h2>Sender Details</h2>
                <div class="info-item">
                    <label for="phq-id">PHQ ID:</label>
                    <span id="phq-id">INDBPLPHQ1542</span>
                </div>
                <div class="info-item">
                    <label for="phq-name">PHQ NAME:</label>
                    <span id="phq-name">PHQ Bhopal (M.P)</span>
                </div>
            </div>

            <div class="requirement-details">
                <h2>Requirement Details</h2>
                <textarea id="reason" rows="6" name="reason" placeholder="Enter reason here..." required></textarea>
                <div class="jawans">
                    <div class="input-group">
                        <label for="male-jawans">Male Jawans:</label>
                        <input type="number" id="male-jawans" name="male_required" placeholder="Number required" required>
                    </div>
                    <div class="input-group">
                        <label for="female-jawans">Female Jawans:</label>
                        <input type="number" id="female-jawans" name="female_required" placeholder="Number required" required>
                    </div>
                </div>
                <div class="location">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" placeholder="Enter location" required>
                </div>
            </div>
            <div class="station_button">
                <button type="button" class="station_btn">Send to Stations</button>
                <div class="station_list">
                    <?php
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

                    // Fetch data from the database
                    $sql = "SELECT * FROM station_details";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "
                            <input type='checkbox' id='station_$row[station_id]' name='email[]' value='$row[station_email]'>
                            <label for='station_$row[station_id]'>$row[station_name]</label>
                        ";
                        }
                    } else {
                        echo "No data found.";
                    }
                    // Close connection
                    mysqli_close($conn);
                    ?>
                    <br>
                    <input type="checkbox" id="selectAllStations" onclick="toggleCheckboxes(this, '.station_list input[type=checkbox]')">
                    <label for="selectAllStations">Select All Stations</label>

                    <button type="submit" class="sender_station" name="send">Send</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        var stationButton = document.querySelector(".station_btn");
        var stationList = document.querySelector(".station_list");

        stationButton.addEventListener("click", function() {
            var display = window.getComputedStyle(stationList).getPropertyValue("display");
            if (display === "none") {
                stationList.style.display = "block";
            } else {
                stationList.style.display = "none";
            }
        });


        function toggleCheckboxes(button, selector) {
            var checkboxes = document.querySelectorAll(selector);
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = button.checked;
            });
        }
    </script>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>