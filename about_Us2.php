<!DOCTYPE html>
<html lang="en">

<?php include 'db_connect.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="nav.css">

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #F4F1E1;
        margin: 0;
        padding: 0;
        color: #003366;
    }

    .banner {
        width: 100%;
        background: linear-gradient(135deg, #D6C4A1 30%, #F4F1E1 100%);
        color: #003366;
        padding: 60px 20px 30px;
        text-align: center;
        margin-top: 40px;
    }

    .banner h1 {
        font-size: 42px;
        margin-bottom: 10px;
    }

    .banner p {
        font-size: 18px;
        margin-top: 5px;
        animation: typing 4s steps(50) 1s forwards, blink 0.75s step-end infinite;
        overflow: hidden;
        white-space: nowrap;
        border-right: 3px solid rgba(0, 0, 0, 0.75);
        width: 0;
        display: inline-block;
    }

    .container {
        max-width: 1000px;
        margin: 40px auto;
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #D6C4A1;
        margin-bottom: 20px;
    }

    .profile h2 {
        font-size: 28px;
        color: #003366;
        margin-bottom: 10px;
    }

    .profile .bio {
        font-size: 16px;
        max-width: 750px;
        margin: 15px auto;
        line-height: 1.8;
        color: #003366;
    }

    .profile .contact-info {
        margin-top: 25px;
        font-size: 16px;
        color: #003366;
    }

    .profile .contact-info p {
        margin: 4px 0;
    }

    .toggle-section {
        margin-top: 40px;
    }

    .toggle-header {
        font-size: 20px;
        font-weight: bold;
        background-color: #800020;
        color: #F4F1E1;
        padding: 15px;
        border-radius: 10px;
        cursor: pointer;
        text-align: center;
    }

    .toggle-content {
        display: none;
        margin-top: 15px;
        padding: 20px;
        background-color: #F4F1E1;
        border-radius: 10px;
        line-height: 1.6;
        color: #003366;
    }

    .toggle-content.show {
        display: block;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes typing {
        from { width: 0; }
        to { width: 100%; }
    }

    @keyframes blink {
        50% { border-color: transparent; }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
        .banner h1 {
            font-size: 32px;
        }

        .profile h2 {
            font-size: 24px;
        }
    }
</style>
    
</head>

<body>

    <ul class="navbar">
        <li><a href="index.php">UNIHUB</a></li>
        <li><a href="loginPage.php">LOGIN</a></li>
        <li><a href="about_Us2.php">ABOUT US</a></li>
        <li><a href="contact_Us2.php">CONTACT US</a></li>
    </ul>

    <div class="banner">
        <h1>ABOUT THE DEVELOPER</h1>
        <p>Hello! I'm Karell Jane de Guzman, creator of the UNIHUB Cooperative System.</p>
    </div>

    <div class="container">

        <div class="profile">
            <img src="profileKarell.jpg" alt="My Photo">
            <h2>Karell Jane M. de Guzman</h2>
            <p class="bio">
                I am second year BSIT student from Bulacan State University with a passion for building systems that make a difference and contribute to the community.
            </p>
            <p class="bio">
                This website was developed as part of the final requirements of the course Web Systems and Technologies I. This aims to support the digital transformation of the online account receivable system. <br> <br>
                This website is designed using <strong>PHP, MySQL, HTML, CSS,</strong> and <strong>JavaScript </strong>
            </p>
            <div class="contact-info">
                <p><strong>Contact:</strong> 0968-890-6796</p>
                <p><strong>Email:</strong> 2023106199@ms.bulsu.edu.ph</p>
            </div>
        </div>

        <div class="toggle-section">
            <div class="toggle-header" onclick="toggleContent('coop')">Project Overview</div>
            <div id="coop" class="toggle-content">
                To design and implement a secure, efficient, and user-friendly online system to
                manage the accounts receivable processes of a multipurpose cooperative. The system
                will streamline invoicing, payment tracking, and reporting, offering transparency and
                accessibility to both administrators and members.
            </div>
        </div>

        <div class="toggle-section">
            <div class="toggle-header" onclick="toggleContent('features')">System Highlights</div>
            <div id="features" class="toggle-content">
                ✦ User Management<br>
                ✦ Accounts Receivable Management<br>
                ✦ Financial Reporting<br>
                ✦ Notifications and Alerts<br>
                ✦ Data Security and Backup
            </div>
        </div>
    </div>

    <script>
        function toggleContent(id) {
            const content = document.getElementById(id);
            content.classList.toggle('show');
        }
    </script>

</body>
</html>