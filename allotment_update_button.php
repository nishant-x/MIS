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

$case_number = $_SESSION['case_number'];
$sql = "SELECT * FROM case_study_of_jawaan WHERE case_number= '$case_number'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$jawaan_name1 = $row['jawaan_name'];

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
}

// Action will be handled here of both button.
// Case submit button action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update"])) {
        $case_num = $case_number;
        $jawaan_name = mysqli_real_escape_string($conn, $_POST["name"]);
        // Handle file uploads
        // Insert data into the database
        $sql3 = "SELECT * FROM `jawaan_details` WHERE `jawaan_name`='$jawaan_name'";
        $result3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($result3);
        $jawaan_id = $row3['jawaan_id'];

        $sql2 = "UPDATE `case_study_of_jawaan` SET `jawaan_name`='$jawaan_name', `jawaan_id`='$jawaan_id' WHERE case_number='$case_num'";
        $result2 = mysqli_query($conn, $sql2);

        if ($result2) {
            echo "<script>
            alert('Allotment Updated');
            window.location.href='station_home.php';
            </script>";
        } else {
            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
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
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>

    <div class="main_content">
        <div class="heading">
            <h2><b>UPDATE CASE REPORT</b></h2>
        </div>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <section class="pre_details">
                <label>Case Number: <?php echo $case_number; ?></label>
            </section>

            <section class="pre_details">
                <label>Name:</label>
                <textarea name="name" id="" cols="2" rows="4"><?php echo $jawaan_name1; ?></textarea>
            </section>

            <div class="button">
                <button type="submit" class="submit" name="update">Update Allotment</button>
        </form>
    </div>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>
