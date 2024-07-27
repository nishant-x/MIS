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
// Fetch data from the database
$sql = "SELECT * FROM station_details WHERE station_id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$station_id = $row["station_id"];
$station_name = $row["station_name"];

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
    $mail->Username = 'station.bhopal04@gmail.com'; // Enter your Gmail email address
    $mail->Password = 'avrd qdcw dpim dqad'; // Enter your Gmail password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;


    $mail->setFrom('station.bhopal04@gmail.com'); //Sender email

    $selectedStations = $_POST["email"];

    foreach ($selectedStations as $email) {
        $mail->addAddress($email); //Receiver email
    }

    $mail->isHTML(true);
    $subject = "EMERGENCY ORDER";
    $message = "Reason: " . $_POST["reason"] . "<br>";
    $message .= "Male Required: " . $_POST["male-jawans"] . "<br>";
    $message .= "Female Required: " . $_POST["female-jawans"] . "<br>";
    $message .= "Location: " . $_POST["location"] . "<br>";

    $mail->Subject = $subject;
    $mail->Body = $message;

    if ($mail->send()) {
        echo "<script>alert('Order Sent Successfully');
        window.location.href='station_home.php';
        </script>";
    } else {
        echo "<script>alert('Error: Unable to send Order.');
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
    <title>Order Panel for Police Station</title>
    <link rel="stylesheet" href="orderpanelforstation.css">
    <style>
       .logout-btn {
            background-color: #0056b3;;
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
        .logoutform {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    justify-content: space-between;
}
    </style>
</head>

    <body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>
   <form method="GET" class='logoutform'>
    
        <?php
    echo "<h3 style='color: darkblue;'>Station Name : $station_name</h3>";
?>

        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>

    <div class="container">
        <h1>ORDER PANEL</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
            <div class="sender_station_info">
                <h5>STATION ID : <span><?php echo "$station_id" ?></span> </h5>
                <h5>STATION NAME : <span> <?php echo "$station_name" ?></span></h5>
            </div>
            <div class="station_requerment_details">
                <textarea name="reason" cols="25" rows="10" placeholder="Mention reason here..." required></textarea>
                <h4>Required jawans</h4>
                <input type="number" name="male-jawans" placeholder="Male jawans Required" required>
                <input type="number" name="female-jawans" placeholder="Female jawans Required" required>
                <br><br>
                <h4>Location:</h4>
                <button id="button_location" onclick="getLocation()">Get Location</button>
                <div id="result" name="location"></div>

                <script>
                  function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition);
                    } else {
                        document.getElementById("result").innerHTML = "Geolocation is not supported by this browser.";
                    }
                 }

                 function showPosition(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    var accuracy = position.coords.accuracy;

                    var result = "Latitude: " + latitude + "<br>Longitude: " + longitude;

                    // Perform reverse geocoding using OpenStreetMap Nominatim API
                    var url = "https://nominatim.openstreetmap.org/reverse?lat=" + latitude + "&lon=" + longitude +
                        "&format=json";

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            // Extract address components from the response
                            var address = data.display_name;
                            result += "<br>Address: " + address;
                            document.getElementById("result").innerHTML = result;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById("result").innerHTML = "Error fetching address.";
                        });
                  }
                </script>
                <!-- <input type="text" name="location" placeholder="Enter location" required> -->
            </div>
            <div class="order">
                <h5>SEND TO</h5>
                <div class="order_button">
                    <div class="police_button">
                        <button class="police_btn">Police Officer</button>
                        <div class="police_list">
                            <!-- PHP CODE -->
                            <?php
                            // Fetch data from the database
                            $sql = "SELECT * FROM station_details WHERE station_id='$id'";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $station_name = $row["station_name"];
                                $sql2 = "SELECT * FROM jawaan_details WHERE station_name='$station_name' AND  jawaan_status= 'active' ";
                                $result2 = mysqli_query($conn, $sql2);

                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                    echo "
                                        <input type='checkbox' id='jawaan_$row2[jawaan_id]' name='email[]' value='$row2[email]'>
                                        <label for='jawaan_$row2[jawaan_id]'>$row2[jawaan_name]</label>
                                    ";
                                }
                            } else {
                                echo "No data found.";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="station_button">
                        <button class="station_btn">PER-STATION</button>
                        <div class="station_list">
                            <?php
                            // Fetch data from the database
                            $sql = "SELECT * FROM station_details";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    if ($station_name != $row["station_name"]) {
                                        echo "
                                            <input type='checkbox' id='station_$row[station_id]' name='email[]' value='$row[station_email]'>
                                            <label for='station_$row[station_id]'>$row[station_name]</label>
                                        ";
                                    }
                                }
                            } else {
                                echo "No data found.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="sender_station" name="send">SEND</button>
            </div>
        </form>
    </div>

    <script>
    var policeButton = document.querySelector(".police_btn");
    var stationButton = document.querySelector(".station_btn");
    var policeList = document.querySelector(".police_list");
    var stationList = document.querySelector(".station_list");

    policeButton.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission behavior
        var display = window.getComputedStyle(policeList).getPropertyValue("display");
        if (display === "none") {
            policeList.style.display = "block";
        } else {
            policeList.style.display = "none";
        }
    });

    stationButton.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission behavior
        var display = window.getComputedStyle(stationList).getPropertyValue("display");
        if (display === "none") {
            stationList.style.display = "block";
        } else {
            stationList.style.display = "none";
        }
    });
    </script>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>