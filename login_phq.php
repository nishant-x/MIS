<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "mis";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Error" . mysqli_connect_error());
}

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $enteredCaptcha = $_POST["captcha"];

    // Verify captcha
    if ($_SESSION['captcha'] == $enteredCaptcha) {
        $sql = "SELECT * FROM phq_login WHERE phq_id='$username' AND phq_password='$password'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $login = true;
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("location: phq_home.php");
        } else {
            $showError = "<span style='color: red;'>Invalid username or password</span>";
        }
    } else {
        $showError = "<span style='color: red;'>Invalid captcha</span>";
    }
}

// Generate random captcha string
$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
$_SESSION['captcha'] = $randomString;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="login.css">
    <style>
        /* Add custom styles for captcha */
        /* Add custom styles for captcha */
.captcha {
    display: flex;
    align-items: center;
}

.captcha input[type="text"] {
    margin-right: 10px;
    padding: 5px;
    font-size: 16px;
    width: 120px;
    margin-top: 4px;
}

.captcha .refresh_button {
    background-color: white;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 0;
    cursor: pointer;
    width: auto;
    font-size: 20px;
    line-height: 1;
}

.captcha .refresh_button:hover {
    background-color:white;
}



        .error-message {
            margin-top: 5px;
        }

        input#captcha {
    margin-top: 4px;
}
button.refresh_button {
    margin-top: 3px;
}
    </style>
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="">
    </nav>
    <section class="main_login">
        <div class="container">

            <div class="login_div leftdiv">
            <img src="3logo3.png" alt="">
                <h3>Welcome to MP Police Central Command</h3>
                <h1> Ministry of Home Affairs</h1>
            </div>

            <div class="login_div rightdiv">
                <h2>Sign in to your account</h2>
                <h4>Please enter your credentials below</h4>
         
                <!-- Display error message if login failed -->
                <?php if ($showError): ?>
                    <div class="error-message"><?php echo $showError; ?></div>
                <?php endif; ?>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                     <!-- Captcha code -->
                     <div class="captcha">
                        <input type="text" value="<?php echo $_SESSION['captcha']; ?>" disabled />
                        <button type="button" class="refresh_button" onclick="refreshCaptcha()">🔄</button>
                    </div>
                    <div class="form-group">
                        <label for="captcha"></label>
                        <input type="text" id="captcha" name="captcha" placeholder="Enter Captcha" required>
                    </div>
                    <!-- /////// -->
                    <h6> <a href="forgot_phq.php">forgot password?</a></h6>

                    <button type="submit">SIGN IN</button>
                </form>
            </div>
        </div>
    </section>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhay Pradesh. All Rights Reserved.</p>
    </footer>
    <script>
        // Function to refresh captcha
        function refreshCaptcha() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.querySelector(".captcha input[type='text']").value = this.responseText;
                }
            };
            xhttp.open("GET", "generate_captcha.php", true);
            xhttp.send();
        }
    </script>
</body>

</html>