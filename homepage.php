<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP Police Central Command</title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .header {
            background-color: #004a8f;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .header img {
            width: 100px;
            height: 100px;
            margin-right: 20px;
            /* Add some space between the logo and the portal name */
        }

        .nav_bar {
            background-color: #0072bc;
            padding: 10px 0;
            text-align: center;
            overflow: hidden;
        }

        .nav_bar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            /* Use flexbox */
            justify-content: center;
            /* Center align the items */
        }

        .nav_bar li {
            margin: 0;
            padding: 0;
            margin-right: 20px;
            /* Add some space between the items */
        }

        .nav_bar a {
            display: inline-block;
            color: #fff;
            text-decoration: none;
            /* padding: 10px 12px;  */
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .nav_bar a:hover {
            background-color: #005c95;
        }

        /* Active state for navigation */
        .nav_bar a.active {
            background-color: #005c95;
        }




        .fixed-nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }






        /* Added style for portal name */
        .portal_name {
            margin-left: 20px;
            /* Add some space between the logo and the text */
            font-size: 1.5em;
            /* Larger font size for portal name */
        }





        .main_body {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            padding: 20px 0;
            margin-top: 20px
        }

        .login {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }

        .login h1,
        .login h3 {
            color: #0072bc;
        }

        .login img {
            width: 100%;
            max-width: 200px;
            margin-top: 20px;
        }

        .btn {
            background-color: #0072bc;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #005c95;
        }

        .services {
            padding: 20px;
            background-color: #fff;
            margin-top: 50px;
        }

        .service {
            margin-bottom: 20px;
        }

        .service h2 {
            color: #0072bc;
        }

        .aboutus {
            background-color: #fff;
            padding: 20px;
            margin-top: 50px;
        }

        .about-section h2 {
            color: #0072bc;
        }

        footer {
            background-color: #004a8f;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
        }

        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .marquee-container {
            background-color: #f4f4f4;
            /* padding: 10px; */
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .important-update {
            margin-right: 20px;
            color: #004a8f;
        }

        .blink-text {
            animation: blink 1s infinite;
            font-weight: bold;
        }

        .marquee-links a {
            margin-left: 20px;
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .marquee-links a:hover {
            color: #0072bc;
        }

        /* Add this CSS code inside the <style> tag in your HTML */
        /* Add this CSS code inside the <style> tag in your HTML */
        #chat-btn {
            position: fixed;
            bottom: 20px;
            /* Adjust as needed */
            right: 20px;
            /* Adjust as needed */
            z-index: 1000;
            /* Ensure it's above other elements */
            background-color: yellowgreen;
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #chat-btn:hover {
            background-color: darkolivegreen;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png" alt="Police Logo">
    </nav>
    <nav class="nav_bar">
        <div class="navbar-container">
            <ul>
                <li><a href="homepage.php" class="home-link">HOME</a></li>
                <li><a href="login.php" class="jawan-login">JAWAN LOGIN</a></li>
                <li><a href="login_station.php" class="station-login">STATION LOGIN</a></li>
                <li><a href="login_phq.php" class="headquarter-login">PHQ LOGIN</a></li>
                <li><a href="#services" class="service-link">SERVICES</a></li>
                <li><a href="#AboutUS" class="aboutus-link">ABOUT US</a></li>
            </ul>
        </div>
    </nav>
    <marquee behavior="scroll" direction="left" scrollamount="10" class="marquee-container">
        <span class="important-update">
            <strong>🔔 Important Update:</strong> Exciting new features added to MP Police Central Command! Check them out now! <strong>🚀</strong>
        </span>
        <span class="blink-text">|| Stay safe and informed ||</span>
        <span class="marquee-links">
            <a href="https://mpdial100.in/About#:~:text=MP%20Police%20is%20committed%20to,Desh%20Bhakti%2C%20Jan%20Seva'." style="color: red; text-decoration: none;">🚨 Emergency Contact: Dial 100</a> |
            <a href="https://www.indiatoday.in/" style="color: blue; text-decoration: none;">📰 Latest News & Updates</a> |
            <a href="https://www.instagram.com/mppolicedeptt/?hl=en style=" color: green; text-decoration: none;">👥 Join Community Policing Programs</a> |
            <a href="https://police.ucla.edu/prevention-education/personal-safety-tips" style="color: orange; text-decoration: none;">🛡 Safety Tips for Citizens</a>
        </span>
    </marquee>



    <section class="main_body" id="main_body">
        <div class="login" id="jawan">
            <h1>JAWAN LOGIN</h1>
            <h3>Secure Access for Jawans</h3>
            <img src="https://hyderabadpolice.gov.in/img/officers/AddlCPCrimes.png" alt="Jawan">
            <a href="login.php">
                <button class="btn">LOGIN</button>
            </a>
        </div>
        <div class="login" id="station">
            <h1>STATION LOGIN</h1>
            <h3>Station Authentication</h3>
            <img src="https://cdn.bignewsnetwork.com/ani1677408014.jpg" alt="Station">
            <a href="login_station.php">
                <button class="btn">LOGIN</button>
            </a>
        </div>
        <div class="login" id="Headquater">
            <h1>PHQ LOGIN</h1>
            <h3>Headquarters Access</h3>
            <img src="https://www.findeasy.in/wp-content/uploads/2020/08/Bhopal-Police-Headquarters.jpg" alt="PHQ">
            <a href="login_phq.php">
                <button class="btn">LOGIN</button>
            </a>
        </div>
    </section>

    <div class="services" id="services">
        <section class="service">
            <h2>Emergency Response</h2>
            <p>For immediate assistance, dial 100 or your local emergency number.</p>
        </section>
        <section class="service">
            <h2>Crime Reporting</h2>
            <p>To report a crime or provide information, contact your local police station.</p>
        </section>
        <section class="service">
            <h2>Traffic Control</h2>
            <p>For traffic-related issues or accidents, call the traffic police helpline.</p>
        </section>
        <section class="service">
            <h2>Women Safety</h2>
            <p>If you are in need of support for women's safety, contact the National Domestic Violence Hotline</p>
        </section>
        <section class="service">
            <h2>Community Policing</h2>
            <p>Get involved in community policing programs and initiatives to improve safety in your area.</p>
        </section>
    </div>

    <div class="aboutus" id="AboutUS">
        <section class="about-section">
            <h2>Our Mission</h2>
            <p>Our mission is to serve and protect the community by upholding the law, ensuring public safety, and promoting peace and harmony.</p>
        </section>
        <section class="about-section">
            <h2>Our Values</h2>
            <ul>
                <li>Integrity</li>
                <li>Professionalism</li>
                <li>Accountability</li>
                <li>Respect</li>
                <li>Community Partnership</li>
            </ul>
        </section>
        <section class="about-section">
            <h2>Our Team</h2>
            <ul>
                <li>Member 1: PIYUSH JAIN</li>
                <li>Member 2: KUNAL NAGWANSHI</li>
                <li>Member 3: NISHANT JHADE</li>
                <li>Member 4: KRISH SINGHAI</li>
                <li>Member 5: ANSHIKA TOMAR</li>
            </ul>
        </section>
    </div>

    <script>
        var main_tab = document.getElementById('main_body');
        var services_tab = document.getElementById('services');
        var aboutUs_tab = document.getElementById('AboutUS');
        var homeLink = document.querySelector('.home-link');
        var serviceLink = document.querySelector('.service-link');
        var aboutUsLink = document.querySelector('.aboutus-link');

        homeLink.addEventListener('click', function(event) {
            main_tab.style.display = "flex";
            services_tab.style.display = "none";
            aboutUs_tab.style.display = "none";
        });

        serviceLink.addEventListener('click', function(event) {
            services_tab.style.display = "flex";
            main_tab.style.display = "none";
            aboutUs_tab.style.display = "none";
        });

        aboutUsLink.addEventListener('click', function(event) {
            aboutUs_tab.style.display = "block";
            main_tab.style.display = "none";
            services_tab.style.display = "none";
        });

        // Fixed navbar script
        window.addEventListener('scroll', function() {
            var navBar = document.querySelector('.nav_bar');
            if (window.scrollY > navBar.offsetHeight) {
                navBar.classList.add('fixed-nav');
            } else {
                navBar.classList.remove('fixed-nav');
            }
        });


        
    </script>

    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
        <button id="chat-btn">Chat with AI</button>
    </footer>
    <script>
        // Simple chatbot function
        function chatBot() {
            // Get user input
            var userInput = prompt("How can I assist you today?");

            // Define AI responses
            var responses = {
                "hello": "Hello! How can I help you today?",
                "services": "Our services include emergency response, crime reporting, traffic control, women's safety, and community policing.",
                "about us": "Our mission is to serve and protect the community. We value integrity, professionalism, accountability, respect, and community partnership.",
                "jawan login": "For Jawan login, you can click on the 'JAWAN LOGIN' button on the homepage.",
                "station login": "For Station login, you can click on the 'STATION LOGIN' button on the homepage.",
                "PHQ login": "For PHQ login, you can click on the 'PHQ LOGIN' button on the homepage.",
                "emergency contact": "In case of emergencies, dial 100 for immediate assistance.",
                "latest news": "You can find the latest news and updates on our 'NEWS & UPDATES' page.",
                "community policing": "To join community policing programs, visit our 'COMMUNITY POLICING' page.",
                "safety tips": "For safety tips for citizens, visit our 'SAFETY TIPS' page.",
                "important update": "For important updates, keep an eye on our website's announcements or visit our 'NEWS & UPDATES' page.",
                "contact us": "You can contact us by visiting the 'CONTACT US' section on our homepage.",
                "FAQ": "For frequently asked questions, visit our 'FAQ' page.",
                "report a crime": "To report a crime or provide information, contact your local police station or use our online reporting system.",
                "lost and found": "If you've lost something or found something valuable, report it to your local police station.",
                "traffic control": "For traffic-related issues or accidents, call the traffic police helpline or report it to your nearest police station.",
                "women safety": "If you need support for women's safety, contact the National Domestic Violence Hotline or visit our 'WOMEN SAFETY' page.",
                "thanks": "You're welcome! If you have any more questions, feel free to ask.",
                "default": "I'm sorry, I didn't understand that. How can I assist you?"

            };

            // Process user input and provide response
            var response = responses[userInput.toLowerCase()] || responses["default"];
            alert(response);
        }

        // Call the chatBot function when the user clicks on the chat button
        document.getElementById('chat-btn').addEventListener('click', chatBot);
    </script>
</body>

</html>