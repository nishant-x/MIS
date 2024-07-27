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
    // Redirect the user to the login page if not logged in  and the id is retrieved from login page
    header("Location: login.php");
    exit(); // Stop further execution
}

$id = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $date = $_POST["date"];
    // Handle file upload
    $certificate_name = $_FILES["achievement_file"]["name"]; // Name of the file
    $certificate_tmp_name = $_FILES["achievement_file"]["tmp_name"]; // Temporary file path

    // Get the file extension
    $file_extension = strtolower(pathinfo($certificate_name, PATHINFO_EXTENSION));

    // Validate file type
    if ($file_extension != "jpg" && $file_extension != "jpeg") {
        echo "
        <script>
        alert('Error: Only JPG and JPEG files are allowed...please fill the form again')
        window.location.href='achievements.php';
        </script>";
        exit(); // Stop further execution
    }

    // Move the uploaded file to the desired location
    $upload_dir = "uploads/"; // Directory where you want to store uploaded files
    $target_file = $upload_dir . basename($certificate_name);
    if (move_uploaded_file($certificate_tmp_name, $target_file)) {
        // File uploaded successfully, now insert into database
        $sql = "INSERT INTO achievement_of_jawaan (jawaan_id, title, description, date, achievement_file) VALUES ('$id', '$title', '$description', '$date', '$certificate_name')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<Script>
            alert('Achievement Added Successfully');
            window.location.href='jawan_home.php';
            </Script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error uploading file.";
    }
}
// Check if logout button is clicked
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to homepage after logout
    header("location: homepage.php");
    exit; // Exit after redirect
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievement Add/Update</title>
    <link rel="stylesheet" href="achievements.css">
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
        <img src="3logo3.png" alt="logo">
    </nav>
<div class="log">
    <form id = "logout" method="GET">
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>
    </div>
    <div class="container">
        <h1> Add Achievement</h1>
        <form method="post" id="achievementForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="achievementTitle">Title</label>
                <input type="text" id="achievementTitle" name="title" required>
            </div>
            <div class="form-group">
                <label for="achievementDescription">Description</label>
                <textarea id="achievementDescription" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="achievementDate">Date</label>
                <input type="date" id="achievementDate" name="date" required>
            </div>
            <div class="form-group">
                <label id="note" for="achievementfile">Certificate*</label>
                <input type="file" id="achievementfile" name="achievement_file" accept=".jpg,.jpeg" required>
                <p class="note"><b>*NOTE:You must enter image in the form of jpg/jpeg.</b></p>
            </div>
            <div class="form-group">
                <input class="button" type="submit" value="Submit">
            </div>
            <?php
            // Fetch and display past achievements with file extension jpg or jpeg
            $sql = "SELECT * FROM achievement_of_jawaan WHERE jawaan_id='$id' AND (achievement_file LIKE '%.jpg' OR achievement_file LIKE '%.jpeg')";
            $result = mysqli_query($conn, $sql);

            echo "<div class='past_achievements' id='past_achievements'>
                        <h2>Past Achievements</h2>
                        <table id='achievementTable'>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody id='achievementList'>";

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                                <td>" . $row['title'] . "</td>
                                <td>" . $row['description'] . "</td>
                                <td>" . $row['date'] . "</td>
                                <td><a href='uploads/" . $row['achievement_file'] . "' target='_blank'>View Certificate</a></td>
                              </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No achievements found.</td></tr>";
            }

            echo "</tbody></table></div>";

            mysqli_close($conn);
            ?>
        </form>
    </div>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>