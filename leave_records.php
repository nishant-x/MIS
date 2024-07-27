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
    header("location: homepage.php");
    exit; // Exit after redirect
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Records</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
}


.header {
    background-color: #0056b3;
    /* Change brown to blue */
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-bottom: 20px;
}

.header img {
    height: 100px;
    width: 100px;
    border-radius: 50%;
}

.portal_name {
    font-size: 30px;
    font-weight: bolder;
}

        .leave_application {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            padding: 20px;
            margin-bottom: 20px;
        }

        .leave_application h2 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        @media only screen and (max-width: 600px) {
            /* Responsive table */
            table {
                border: 0;
            }

            table thead {
                display: none;
            }

            table tr {
                margin-bottom: 20px;
                display: block;
                border-bottom: 2px solid #ddd;
            }

            table td {
                display: block;
                text-align: right;
                border-bottom: 0;
            }

            table td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
        }
        
footer {
    background-color: #0056b3;
    color: #fff;
    padding: 20px;
    text-align: center;
    font-family: Arial, sans-serif;
    font-size: 14px;
    border-top: 2px solid #fff;
    box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
}

footer:hover {
    background-color: #003f7f;
    transition: background-color 0.3s ease;
}

footer p {
    margin: 0;
}

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
    <div class="leave_application" id="leave-application">
        <h2>LEAVE APPLICATION</h2>
        <div class="application_status">
    
               <?php
             
            $id=$_SESSION['username'];
            $sql_leave = "SELECT * FROM apply_for_leave WHERE jawaan_id='$id'";
            $result_leave = mysqli_query($conn, $sql_leave);

            if (mysqli_num_rows($result_leave) > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                
                                <th>TYPE OF LEAVE</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = mysqli_fetch_assoc($result_leave)) {
                    echo "<tr>
                            <td data-label='START DATE'>" . $row['date_from'] . "</td>
                            <td data-label='END DATE'>" . $row['date_to'] . "</td>
                           
                            <td data-label='TYPE OF LEAVE'>" . $row['reason'] . "</td>
                            <td data-label='STATUS'>" . $row['status'] . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "No leave applications found.";
            }
            ?>
        </div>
    </div>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
</body>
</html>