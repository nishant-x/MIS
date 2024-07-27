<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST["send"])) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'misportal04@gmail.com'; // Enter your Gmail email address
    $mail->Password = 'ucbg kmko xdua ioer'; // Enter your Gmail password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('misportal04@gmail.com'); //Sender email

    $mail->addAddress($_POST["email"]);      //Receiver email

    $mail->isHTML(true);
    $subject = "FORGOT PASSWORD";
    $message = "Your Password is:123"; // Predefined message
    $mail->Subject = $subject;
    $mail->Body = $message;
    

    $mail->send();

    echo 
    "
    <script>
    alert('Check your mail to get Password');
    window.location.href='login.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgot</title>
    <link rel="stylesheet" href="forgot.css">
</head>

<body>
    <section class="main_login">
        <nav class="header">
            <p class="portal_name">MP Police Central Command</p>
            <img src="3logo3.png" alt="">
        </nav>
        <div class="container">
            <div class="login_div leftdiv">
            <img src="3logo3.png" alt="">
                <h3>Welcome to MP Police Central Command</h3>
                <h1> Ministry of Home Affairs</h1>
            </div>

            <div class="login_div rightdiv">
                <form  action="" method="post">
                    <div class="form-group">
                        <h1>RESET PASSWORD</h1>
                        <h5>You can get your password here</h5>
                        
                        <!-- <input type="text" name="subject" value="Forgot Password"><br>
                         <input type="text" name="message" value="jawaan@123"> <br> -->
                        
                        <input type="text" placeholder="Email Address" name="email">
                    </div>
                    <h6> <a href="login.php"> back to <span> login</span></a></h6>

                    <button type="submit" name="send">SEND MY PASSWORD</button>
                </form>
            </div>
        </div>
        <footer>
            <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
        </footer>
    </section>


</body>

</html>