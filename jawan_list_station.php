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
$sql = "SELECT * FROM station_details WHERE station_id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    // Display user profile information
    $station = $user['station_name'];
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
        form {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}
    </style>
    <script>
        function redirectToDetails(caseNumber) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'jawan_profile_for_list.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'jawaan_name';
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
    echo "<h3 style='color: darkblue;'>Station Name : $station</h3>";
?>

        <button class="logout-btn" type="submit" name="logout">Logout</button>
        </form>

    <header>
        <h1>Jawan List</h1>
        <form action="" method="GET">
            <input type="text" name="search" id="search" placeholder="Search Jawans.." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </header>

    <div class="container">
        <?php
        // Modify SQL query to include search functionality
        $search_query = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT * FROM jawaan_details WHERE station_name='$station'";

        if (!empty($search_query)) {
            // If search query is provided, add filter condition
            $sql .= " AND (jawaan_name LIKE '%$search_query%'OR jawaan_id LIKE '%$search_query%' OR post LIKE '%$search_query%' OR contact LIKE '%$search_query%' OR email LIKE '%$search_query%')";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Encode the jawaan_name to ensure it doesn't contain special characters
                $encoded_name = urlencode($row['jawaan_name']);
                echo "
                     
                <div class='jawan' onclick=\"redirectToDetails('" . htmlspecialchars($row['jawaan_name']) . "')\">
                            <div class='jawan_img'>
                                <img src='data:image/jpeg;base64," . base64_encode($row['profile_img']) . "' alt='jawan'>
                            </div>
                            <div class='jawan_details'>
                                <h2>" . $row['jawaan_name'] . "</h2>
                                <p>Rank: " . $row['post'] . "</p>
                                <p>Station: " . $row['station_name'] . "</p>
                                <p>Phone: " . $row['contact'] . "</p>
                                <p>Email: " . $row['email'] . "</p>
                                <p>Active Status: " . $row['jawaan_status'] . "</p>
                            </div>
                        </div>
                   
                ";
            }
        } else {
            echo "No Jawan found.";
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>
