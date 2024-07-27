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
    
    $mail->Subject= $_POST["subject"];
    //$mail->Subject= $_POST[""];
    
    $mail->Body= $_POST["message"];
    //$mail->Body= $_POST["male"];
   // $mail->Body= $_POST["female"];
    //$mail->Body= $_POST["location"];

    $mail->send();

    echo 
    "
    <script>
    alert('Sent Successfully');
    document.location.href='forgot.php';
    </script>
    ";
}

?>
