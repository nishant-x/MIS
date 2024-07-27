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

$case_number = ""; // Initialize $case_number variable

// Check if case_number is passed from the form
if (isset($_POST['case_number'])) {
    $case_number = $_POST['case_number'];
    $sql = "SELECT * FROM case_study_of_jawaan WHERE case_number= '$case_number'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $incident_report = $user['incident_report'];
        $investigation_reports = $user['investigation_notes'];
        $witness_statements = $user['witness_statement'];
    } else {
        // If no matching case is found, display an error message
        echo "Error: No matching case found.";
    }
} else {
    // If case_number is not received, show an error message
    echo "Error: Case Number not received.";
}

// Check if form is submitted and the complete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["complete"])) {
    // Update the case status in the database
    $sql = "UPDATE case_study_of_jawaan SET case_status='case-completed' WHERE case_number='$case_number'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect to the jawan_home.php page after successful update
        echo "<script>
        alert('Case Completed');
        window.location.href='station_home.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>

    <div class="main_content">
        <div class="heading">
            <h2><b>CASE REPORT</b></h2>
        </div>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="hidden" name="case_number" value="<?php echo $case_number; ?>"> <!-- Include this line -->
            <section class="pre_details">
                <label>Case Number: <?php echo $case_number; ?></label>
            </section>
            <!-- Rest of your form -->

            <section class="case_details">
                <div class="incident">
                    <h2>Incident Reports</h2>
                    <section class="pre_details">
                        <label><?php echo  $incident_report; ?></label>
                    </section>
                </div>
                <div class="investigation">
                    <h2>Investigation Reports</h2>
                    <section class="pre_details">
                        <label><?php echo $investigation_reports; ?></label>
                    </section>
                </div>
                <div class="witness">
                    <h2>Witness Statements</h2>
                    <section class="pre_details">
                        <label>Case Number: <?php echo $witness_statements; ?></label>
                    </section>
                </div>
                <div class="button">
                    <button type="submit" class="submit" name="complete">Case Complete</button>
                </div>

            </section>
            <section>
                <P><B>NOTE:If you click the complete button the case will be completed by your end. </B></P>
            </section>
        </form>
    </div>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>