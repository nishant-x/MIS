<?php
// Start the session
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

// Reference
$badge = $_SESSION['badge_no.'];
$sql = "SELECT * FROM `jawaan_details` WHERE `badge_no.` = '$badge'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
$jawaan_id = $row["jawaan_id"];
$jawaan_name = $row["jawaan_name"];
$email = $row["email"];
$present_station = $row["station_name"];

// PHP mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Taking data from the HTML form of application
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send"])) {
    $jawaan_id2 = $jawaan_id;
    $badge2 = $badge;
    $jawan_name2 = $jawaan_name;
    $tostation = $present_station; // Correctly handle the selected station
    $forstation = $_POST["forstation"];
    $joining_date = $_POST["joining_date"];
    $reason = $_POST["reason"];

    // Taking the ID of the Jawan
    $sql = "SELECT * FROM `jawaan_details` WHERE `badge_no.` = '$badge'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $jawaan_id = $row["jawaan_id"];
        $email = $row["email"];

        // Inserting data into the database
        $sql2 = "INSERT INTO `jawaan_transfer_orders`(`jawaan_id`, `reason`, `badge_no.`, `tostation`, `forstation`, `joining_date`, `email`) VALUES ('$jawaan_id','$reason','$badge2','$tostation','$forstation','$joining_date','$email')";
        $result2 = mysqli_query($conn, $sql2);

        if ($result2) {
            // Send mail to the Jawan who applied for leave
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'phq.bhopal04@gmail.com'; // Enter your Gmail email address
            $mail->Password = 'jnme mfna vkba xrkq'; // Enter your Gmail password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            // Other mail settings...

            $mail->setFrom('phq.bhopal04@gmail.com');
            $mail->addAddress($email);

            // Mail subject and body
            $subject = "Transferee Order";
            // Email Message
            $message =
                "Dear  $jawaan_name,
I hope this message finds you well.

This is to inform you that, based on the decision of the department, you have been transferred from $tostation to $forstation. Your transfer will be effective from $joining_date.

Transfer Details:

Current Station: $tostation
New Station: $forstation
Effective Date:  $joining_date 
Reason for Transfer: $reason

Please make the necessary arrangements to report to your new station on the specified date. Your cooperation and understanding in this matter are greatly appreciated.

If you have any questions or need further clarification, please do not hesitate to contact the department.

Thank you for your continued dedication and service to the community.";  // content 

            $mail->Subject = $subject;
            $mail->Body = $message;

            try {
                $mail->send();
                echo "<Script>
                alert('Jawan Transferee Order Sent Successfully!!');
                window.location.href='phq_home.php';
                </Script>";
            } catch (Exception $e) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: Jawan with badge number '$badge' not found";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawan Transfer Order Application</title>
    <link rel="stylesheet" href="transfere.css">
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>
    <form action="transferesearch.php" method="post" id="transferbadge">
        <h1>Jawan Transfer Order</h1>
        <div class="user_details">
            <h5>ENTER JAWAN BADGE NUMBER</h5>
            <input type="text" placeholder="Badge number" name="badge" value="<?php echo "$badge"; ?>" required>
            <h5>ENTER JAWAN NAME</h5>
            <input type="text" placeholder="Jawan name" name="jawan_name" value="<?php echo "$jawaan_name"; ?>" required>

            <!-- jawaan's Present station name -->
            <h5>PRESENT STATION</h5>
            <select name="tostation" id="current_station">
                <option value=""><?php echo "$present_station"; ?></option>
            </select>
            <h5>TRANSFER TO STATION</h5>
            <select name="forstation" id="transfer_station" required>
                <option value="">Select Station</option>
                <?php
                // Database connection
                $conn = mysqli_connect($servername, $username, $password, $database);

                // Fetch data from the database
                $sql = "SELECT * FROM station_details";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['station_name'] != $present_station) {
                            echo "<option value='" . $row['station_name'] . "'>" . $row['station_name'] . "</option>";
                        }
                    }
                } else {
                    echo "<option disabled>No stations found</option>";
                }

                // Close connection
                mysqli_close($conn);
                ?>
            </select>
            <h5>JOINING DATE</h5>
            <input type="date" placeholder="dd/mm/yyyy" name="joining_date" required>
        </div>
        <div class="reason">
            <?php
            $currentDate = date('Y-m-d');
            echo '<h5>Date of Issue</h5>
           <input type="date" placeholder="dd/mm/yyyy" name="date_of_issue" value="' . $currentDate . '">';
            ?>


            <h5>Reason</h5>
            <textarea cols="" rows="12" placeholder="Reason for transfer" name="reason" required></textarea>
        </div>
        <div class="button">
            <input type="submit" class="submit" value="send" name="send">

        </div>
    </form>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>

</body>

</html>