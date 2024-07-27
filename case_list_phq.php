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

        #logout,
        .log {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            margin-left: 10px;
            margin-right: 10px;
            justify-content: flex-end;

        }
    </style>
    <script>
        function redirectToDetails(caseNumber) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'view_case_details_phq.php';

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
    <div class="log">
        <form id="logout" method="GET">
            <button class="logout-btn" type="submit" name="logout">Logout</button>
        </form>
    </div>
    <header>
        <h1>Case List</h1>
        <form action="" method="GET">
            <input type="text" name="search" id="search" placeholder="Search Case.." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </header>
    <div class="container">
        <?php


        // Check if user is logged in
        $search_query = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT * FROM case_study_of_jawaan";


        if (!empty($search_query)) {
            // If search query is provided, add filter condition
            $sql .= " WHERE case_number LIKE '%$search_query%' OR crime_type LIKE '%$search_query%' OR status LIKE '%$search_query%' OR location LIKE '%$search_query%'";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $jawaan_id = $row['jawaan_id'];
                $sql2 = "SELECT * FROM jawaan_details WHERE jawaan_id='$jawaan_id'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $jawaan_name = $row2["jawaan_name"];

                echo "
                <div class='jawan' onclick=\"redirectToDetails('" . htmlspecialchars($row['case_number']) . "')\">
                    <div class='jawan_details'>
                        <p><h4> Case No.: " . $row['case_number'] . "</h4></p>
                        <p><h4> Assigned officer: " . $jawaan_name . "</h4></p>  
                        <p> Assigned Officer Contact No.: " . $row2['contact'] . "</p>
                        <p> Crime Type: " . $row['crime_type'] . "</p>
                        <p> Date: " . $row['date'] . "</p>
                        <p> Crime Location: " . $row['location'] . "</p>
                        <p> Case Status: " . $row['case_status'] . "</p>                       
                    </div>
                </div>
                ";
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