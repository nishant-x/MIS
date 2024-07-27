<?php
session_start();

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
$case_number = $_SESSION['case_number'];

$sql = "SELECT * FROM case_study_of_jawaan WHERE case_number= '$case_number' AND jawaan_id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $incident_report = $user['incident_report'];
    $investigation_reports = $user['investigation_notes'];
    $witness_statements = $user['witness_statement'];
}

// Action will be handled here of both button.
// Case submit button action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update"])) {
        $case_num = $case_number;
        $incident = mysqli_real_escape_string($conn, $_POST["incident"]);
        $statement = mysqli_real_escape_string($conn, $_POST["statement"]);
        $investigation = mysqli_real_escape_string($conn, $_POST["investigation"]);
      
            


        
        // Handle file uploads
        // $evidence = $_FILES["evidence"]["name"];
        // $evidence_temp = $_FILES["evidence"]["tmp_name"];
        // $evidence_path = "uploads/" . $evidence;

        // $documentation = $_FILES["documentation"]["name"];
        // $documentation_temp = $_FILES["documentation"]["tmp_name"];
        // $documentation_path = "uploads/" . $documentation;

        // // Move uploaded files to the desired location
        // move_uploaded_file($evidence_temp, $evidence_path);
        // move_uploaded_file($documentation_temp, $documentation_path);

        // Insert data into the database
        $sql = "UPDATE `case_study_of_jawaan` SET `incident_report`='$incident',`witness_statement`='$statement',`investigation_notes`='$investigation' WHERE jawaan_id='$id' AND case_number='$case_num'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
            alert('Case Updated Successfully');
            window.location.href='jawan_home.php';
            </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    // Case complete button action
    if (isset($_POST["complete"])) {
        $case_num = $case_number;
        $incident = mysqli_real_escape_string($conn, $_POST["incident"]);
        $statement = mysqli_real_escape_string($conn, $_POST["statement"]);
        $investigation = mysqli_real_escape_string($conn, $_POST["investigation"]);

        // Handle file uploads
        $evidence = $_FILES["evidence"]["name"];
        $evidence_temp = $_FILES["evidence"]["tmp_name"];
        $evidence_path = "uploads/" . $evidence;

        $documentation = $_FILES["documentation"]["name"];
        $documentation_temp = $_FILES["documentation"]["tmp_name"];
        $documentation_path = "uploads/" . $documentation;

        // Move uploaded files to the desired location
        move_uploaded_file($evidence_temp, $evidence_path);
        move_uploaded_file($documentation_temp, $documentation_path);

        // Insert data into the database
        $sql = "UPDATE `case_study_of_jawaan` SET `incident_report`='$incident',`witness_statement`='$statement',`investigation_notes`='$investigation',`evidence_collection`='$evidence',`documentation`='$documentation',`case_status`='case completed' WHERE jawaan_id='$id' AND case_number='$case_num'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
            alert('Case Updated Successfully');
            window.location.href='jawan_home.php';
            </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
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
            <h2><b>UPDATE CASE REPORT</b></h2>
        </div>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <section class="pre_details">
                <label>Case Number: <?php echo $case_number; ?></label>
            </section>

            <section class="case_details">
                <div class="incident">
                    <h2>Incident Reports</h2>
                    <div class="incident_reports">
                        <textarea placeholder="" name="incident"><?php echo $incident_report; ?></textarea>
                    </div>
                </div>
                <div class="investigation">
                    <h2>Investigation Reports</h2>
                    <div class="investigation_notes">
                        <textarea placeholder="" name="investigation"><?php echo $investigation_reports; ?></textarea>
                    </div>
                </div>
                <div class="witness">
                    <h2>Witness Statements</h2>
                    <div class="witness_statments">
                        <textarea placeholder="" name="statement"><?php echo $witness_statements; ?></textarea>
                    </div>
                </div>

                <!-- <div class="evidence">
                    <h2>Evidence Collection</h2>
                    <div class="evidence_collection">
                        <input type="file" name="evidence">
                    </div>
                </div>

                <div class="documentation">
                    <h2>Documentation</h2>
                    <div class="documentation_collection">
                        <input type="file" name="documentation">
                    </div> -->
                <!-- </div> -->
            </section>

            <div class="button">
                <button type="submit" class="submit" name="update">Update Case</button>
                <!-- <button type="submit" class="submit" name="complete">Case Complete</button> -->
            </div>
        </form>

        <p><b>*NOTE: </b>Press the Complete Case Button When the Case is Solved.</p>
    </div>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>
