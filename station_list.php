<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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

// Default query to fetch all police stations
$sql = "SELECT * FROM station_details";

// Check if the search form is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    // Modify the query to search for a specific police station
    $sql = "SELECT * FROM station_details WHERE station_name LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

// Check if logout button is clicked
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    // Redirect to homepage after logout
    header("location: homepage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Stations</title>
    <link rel="stylesheet" href="station_list.css">
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
        }

        #logout,
        .log {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            margin-left: 10px;
            margin-right: 10px;
            justify-content: flex-end;
        }

        .station {
            cursor: pointer;
        }
    </style>
    <script>
        function redirectToStationDetails(stationName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'station_profile_for_list.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'station_name';
            input.value = stationName;

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
    <div class="log">
        <form id="logout" method="GET">
            <button class="logout-btn" type="submit" name="logout">Logout</button>
        </form>
    </div>
    <header>
        <h1>Station List</h1>
        <form action="" method="GET">
            <input type="text" name="search" id="search" placeholder="Search Station.." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </header>

    <div class="container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='station' onclick=\"redirectToStationDetails('" . htmlspecialchars($row['station_name']) . "')\">
                    <div class='station_img'>
                        <img src='data:image/jpeg;base64," . base64_encode($row['station_image']) . "' alt='station'>
                    </div>
                    <div class='station_details'>
                        <h2>" . htmlspecialchars($row['station_name']) . "</h2>
                        <p>STATION ID: " . htmlspecialchars($row['station_id']) . "</p>
                        <p>LOCATION: " . htmlspecialchars($row['station_address']) . "</p>
                        <p>PINCODE: " . htmlspecialchars($row['pincode']) . "</p>
                        <p>PHONE NO.: " . htmlspecialchars($row['contact_no.']) . "</p>
                    </div>
                </div>";
            }
        } else {
            echo "No matching records found.";
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
