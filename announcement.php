<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Co-UniHub Cooperative</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #F4F1E1, #D6C4A1);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            letter-spacing: .5px;
            line-height: 1.6;
            color: #003366;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero {
            background-color: #003366;
            color: #F4F1E1;
            text-align: center;
            padding: 60px 20px 40px 20px;
            margin-top: 50px;
            width: 100%;
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }

        .hero:hover {
            transform: scale(1.02);
        }

        .hero h1 {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 22px;
        }

        main.content {
            width: 100%;
            max-width: 1000px;
            margin: 60px auto 20px auto;
            padding: 0 20px;
        }

        .announcement-box {
            background: #F4F1E1;
            padding: 30px;
            margin-bottom: 20px;
            border-left: 5px solid #003366;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeSlideUp 0.8s ease forwards;
        }

        .announcement-box:nth-child(1) { animation-delay: 0.3s; }
        .announcement-box:nth-child(2) { animation-delay: 0.6s; }
        .announcement-box:nth-child(3) { animation-delay: 0.9s; }

        @keyframes fadeSlideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .announcement-box:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        .announcement-box h3 {
            margin-top: 0;
            font-size: 22px;
            color: #003366;
        }

        .announcement-box p {
            margin: 10px 0;
        }

        footer {
            display: flex;
            justify-content: space-between;
            background: #003366;
            color: #F4F1E1;
            padding: 20px;
            margin-top: 40px;
            width: 100%;
            box-sizing: border-box;
        }

        footer div {
            flex: 1;
        }

        footer .social {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        footer .social span {
            color: #F4F1E1;
            font-size: 20px;
        }
    </style>
</head>
<body>

<ul class="navbar">
    <li><a href="homepage.php">HOME</a></li>
    <li><a href="profile_Template.php">MY PROFILE</a></li>
    <li><a href="#">NOTIFICATION</a></li>
    <li><a href="helpMembs.php">HELP</a></li>
    <li><a href="index.php">LOG OUT</a></li>
</ul>

<section class="hero">
    <h1>UNIHUB ANNOUNCEMENTS</h1>
    <p>Stay informed with our latest news and updates</p>
</section>

<main class="content">
    <div class="announcement-box">
        <h3>📢 Office Closed on June 12</h3>
        <p>Please be informed that Co-UniHub Cooperative will be closed on June 12, 2025, in observance of Independence Day. Regular operations will resume on June 13, 2025.</p>
    </div>

    <div class="announcement-box">
        <h3>💳 New Loan Packages Now Available</h3>
        <p>Starting this July, members can now apply for our new education and livelihood loan packages. Visit our Loans Department for more details.</p>
    </div>

    <div class="announcement-box">
        <h3>🎉 Member Appreciation Week</h3>
        <p>We’re excited to celebrate Member Appreciation Week this August! Expect giveaways, free seminars, and exciting rewards for our loyal members.</p>
    </div>
</main>

<footer>
    <div class="contact">
        <p>Contact</p>
        <p>3000 Malolos, Bulacan, Philippines</p>
    </div>
    <div class="social">
        <p>Connect</p>
        <span>● ● ●</span>
    </div>
</footer>

</body>
</html>