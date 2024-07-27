<?php
session_start(); // Start the session
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
$sql = "SELECT * FROM station_details WHERE station_id='$id'";
$result = mysqli_query($conn, $sql);

//fetching data from jawaan_details table
if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    // Display user profile information
    $station_name = $user['station_name'];
} else {
    echo "User not found.";
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
    <title>Jawan List</title>
    <link rel="stylesheet" href="jawan_list.css">
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

        form {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .jawan {
            cursor: pointer;
        }
    </style>
    <script>
        function redirectToDetails(caseNumber) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'view_case_details.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'case_number';
            input.value = caseNumber;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="jawaan_img">
    </nav>
    <form method="GET">
        <?php
        echo "<h3 style='color: darkblue;'>Station Name: $station_name</h3>";
        ?>
        <button class="logout-btn" type="submit" name="logout">Logout</button>
    </form>

    <header>
        <h1>Case List</h1>
        <form action="" method="GET">
            <input type="text" name="search" id="search" placeholder="Search Case.." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </header>
    <div class="container">
        <?php
        // Construct SQL query
        $sql = "SELECT * FROM case_study_of_jawaan WHERE station_name='$station_name'";

        // Check if the search form is submitted
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $sql .= " AND (case_number LIKE '%$search%' OR crime_type LIKE '%$search%' OR case_status LIKE '%$search%' OR location LIKE '%$search%' OR station_name LIKE '%$search%')";
        }

        // Execute query
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Fetch assigned officer details
                $jawaan_id = $row['jawaan_id'];
                $sql2 = "SELECT * FROM jawaan_details WHERE jawaan_id='$jawaan_id' AND station_name='$station_name'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
   
                echo "
                <div class='jawan' onclick=\"redirectToDetails('" . htmlspecialchars($row['case_number']) . "')\">
                    <div class='jawan_details'>
                    <p><h4> Case No.: " . $row['case_number'] . "</h4></p>
                    <p><h4> Assigned officer: " . $row2["jawaan_name"] . "</h4></p>  
                    <p> Assigned Officer Contact No.: " . $row2['contact'] . "</p>
                    <p> Crime Type: " . $row['crime_type'] . "</p>
                    <p> Date: " . $row['date'] . "</p>
                    <p> Crime Location: " . $row['location'] . "</p>
                    <p> Case Status: " . $row['case_status'] . "</p>
                    <p> Station Name: " . $station_name . "</p>
                    </div>
                </div>";
            }
        } else {
            echo "No data found.";
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
