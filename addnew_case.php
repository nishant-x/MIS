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

// Ensure the user is logged in and get the user ID from the session
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in and the id is retrieved from the login page
    header("Location: login.php");
    exit(); // Stop further execution
}
$id = $_SESSION['username'];
$sql6 = "SELECT * FROM jawaan_details where jawaan_id='$id'";
$result6 = mysqli_query($conn, $sql6);
$row6 = mysqli_fetch_assoc($result6);
$station_name = $row6['station_name'];


// Check if the form is submitted
//storing the data filled by the user in variables to store it in mysql table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $case_number = mysqli_real_escape_string($conn, $_POST["case_number"]);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $incident = mysqli_real_escape_string($conn, $_POST["incident"]);
    $statement = mysqli_real_escape_string($conn, $_POST["statement"]);
    $investigation = mysqli_real_escape_string($conn, $_POST["investigation"]);

    // Handle file uploads
    $evidence = $_FILES["evidence"]["name"];
    $evidence_temp = $_FILES["evidence"]["tmp_name"];
    $evidence_path = "uploads/" . $evidence; // Adjust this path as needed

    $documentation = $_FILES["documentation"]["name"];
    $documentation_temp = $_FILES["documentation"]["tmp_name"];
    $documentation_path = "uploads/" . $documentation; // Adjust this path as needed

    // Move uploaded files to the desired location
    move_uploaded_file($evidence_temp, $evidence_path);
    move_uploaded_file($documentation_temp, $documentation_path);

    // Insert data into the database
    $sql = "INSERT INTO case_study_of_jawaan (jawaan_id,station_name, case_number, crime_type, date, location, incident_report, witness_statement, investigation_notes, evidence_collection, documentation) 
            VALUES ('$id','$station_name', '$case_number', '$type', '$date', '$location', '$incident', '$statement', '$investigation', '$evidence_path', '$documentation_path')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>
        alert('Case added Successfully');
        window.location.href='jawan_home.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
            <h2><b>CASE REPORT</b></h2>
        </div>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <section class="pre_details">
                <label>Case Number:</label>
                <input type="text" placeholder="Enter Case Number" id="caseNumber" name="case_number">
                <label>Date:</label>
                <input type="date" name="date">
                <label>Location</label>

                <button id="location_button" onclick="getLocation()">Get Location</button>
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
                <label>Crime type</label>
                <input type="text" placeholder="Crime Type" name="type">
            </section>

            <section class="case_details">

                <div class="incident">
                    <h2>Incident Reports</h2>
                    <div class="incident_reports">
                        <textarea placeholder="" name="incident"></textarea>
                    </div>
                </div>
                <div class="investigation">
                    <h2>Investigation Reports</h2>
                    <div class="investigation">
                        <textarea placeholder="" name="investigation"></textarea>
                    </div>
                </div>
                <div class="witness">
                    <h2>Witness Statements</h2>
                    <div class="witness_statments">
                        <textarea placeholder="" name="statement"></textarea>
                    </div>
                </div>

                <div class="evidence">
                    <h2>Evidence Collection</h2>
                    <div class="evidence_collection">
                        <input type="file" name="evidence">
                    </div>
                </div>

                <div class="documentation">
                    <h2>Documentation</h2>
                    <div class="documentation_collection">
                        <input type="file" name="documentation">
                    </div>
                </div>
            </section>
            <div class="button">
                <button type="submit" class="submit" name="submit">Submit Case Details</button>


            </div>

        </form>
    </div>


    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
    </footer>

</body>

</html>