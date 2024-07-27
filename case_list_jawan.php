<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawan List</title>
    <link rel="stylesheet" href="jawan_list.css">
</head>
<body>
<nav class="header">
    <p class="portal_name">MP Police Central Command</p>
    <img src="3logo3.png"  alt="jawaan_img">
</nav>
<header>
    <h1>Case List</h1>
    
    <form action="" method="GET">
        <input type="text" name="search" id="search" placeholder="Search Jawans.." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
    </form>
</header>
<div class="container">
    <?php
    session_start(); // Start the session
    

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

    // Check if user is logged in
    if(isset($_SESSION['username'])) {
        $id = $_SESSION['username'];

        // Fetch data from the database
        $search_query = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT * FROM case_study_of_jawaan WHERE jawaan_id='$id'";
        if (!empty($search_query)) {
            // If search query is provided, add filter condition
            $sql .= " AND (case_number LIKE '%$search_query%' OR crime_type LIKE '%$search_query%' OR status LIKE '%$search_query%' OR location LIKE '%$search_query%')";
        }
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Encode the jawaan_name to ensure it doesn't contain special characters
                $encoded_name = urlencode($row['case_number']);
                $jawaan_id = $row["jawaan_id"];
                $sql2 = "SELECT * FROM jawaan_details WHERE jawaan_id='$jawaan_id' ";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                echo "
                    <div class='jawan'>
                        <div class='jawan_details'>
                            <p><h4> Case No.:" . $row['case_number'] . "</h4></p>
                            <p> Assigned Officer: " . $row2['jawaan_name'] . "</p>
                            <p> Crime Type: " . $row['crime_type'] . "</p>
                            <p> Date: " . $row['date'] . "</p>
                            <p> Crime Location: " . $row['location'] . "</p>
                            <p> Case Status: " . $row['status'] . "</p>
                        </div>
                    </div>
                ";
            }
        } else {
            echo "No data found.";
        }
    } else {
        echo "User not logged in.";
    }

    // Close connection
    mysqli_close($conn);
    ?>
</div>
<footer>
    <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
</footer>
</body>
</html>
