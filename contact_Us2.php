<!DOCTYPE html>
<html lang="en">
<?php include 'db_connect.php'; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="nav.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding-top: 80px;
        }

        .container {
            display: flex;
            background: #fff;
            width: 90%;
            max-width: 1100px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            margin-top: 30px;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .left-side {
            width: 45%;
            background: linear-gradient(135deg, #003366, #800020);
            color: white;
            position: relative;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .mini-container {
            position: relative;
            background-color: #F4F1E1;
            color: #003366;
            padding: 20px;
            border-radius: 15px;
            width: 100%;
            max-width: 320px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #800020;
            margin-bottom: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .mini-container p {
            margin: 10px 0;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mini-container i {
            margin-right: 10px;
            color: #800020;
        }

        .right-side {
            width: 55%;
            padding: 40px 30px;
            background-color: #fff;
        }

        .right-side h2 {
            font-size: 36px;
            text-align: center;
            color: #003366;
            margin-bottom: 10px;
        }

        .right-side p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #003366;
            margin-bottom: 6px;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #B0B0B0;
            border-radius: 8px;
            font-size: 14px;
            background-color: #F4F1E1;
            transition: box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            box-shadow: 0 0 5px #800020;
            outline: none;
        }

        .form-group textarea {
            resize: none;
            height: 100px;
        }

        .send-btn {
            display: block;
            width: 120px;
            padding: 12px;
            background-color: #800020;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            margin: 0 auto;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
        }

        .send-btn:hover {
            background-color: #003366;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-side,
            .right-side {
                width: 100%;
            }

            .left-side {
                padding: 30px 20px;
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

    <div class="container">
        <div class="left-side">
            <div class="mini-container">
                <img src="map.svg" alt="Map Image" class="profile-image">
                <p><i class="fas fa-building"></i> <strong>Company:</strong> Bulacan State University</p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Malolos, Bulacan</p>
                <p><i class="fas fa-phone"></i> <strong>Contact:</strong> 8934-3930</p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> 2023106199@google.com</p>
            </div>
        </div>
        <div class="right-side">
            <h2>GET IN TOUCH</h2>
            <p>Send us a message!</p>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" placeholder="Write your message here..."></textarea>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="Enter your email">
            </div>

            <button class="send-btn">SEND</button>
        </div>
    </div>
</body>
</html>