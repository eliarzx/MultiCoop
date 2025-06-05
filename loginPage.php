<!DOCTYPE html>
<html lang="en">
    <?php 
include 'db_connect.php'; 
?>
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="nav.css">
    <title>Log-in</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .background-waves {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        .background-waves svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .wave1 {
            opacity: 0.6;
            position: absolute; 
        }

        @keyframes waveAnimation {
            0% {
                transform: translateY(0); 
            }
            25% {
                transform: translateY(-10px); 
            }
            50% {
                transform: translateY(0); 
            }
            75% {
                transform: translateY(10px); 
            }
            100% {
                transform: translateY(0); 
            }
        }

        .wave2 {
            opacity: 0.4;
            animation: waveAnimation 8s ease-in-out infinite;
            animation-duration: 12s;
        }

        .wave3 {
            opacity: 0.2;
            animation: waveAnimation 8s ease-in-out infinite;
            animation-duration: 14s;
        }


        .container {
            display: flex;
            width: 80%;
            max-width: 1000px;
            height: 500px;
            background: white;
            margin-top: 150px; 
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .login-section {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            background: linear-gradient(to bottom, #F4F1E1, white);
        }

        .login-section h2 {
            text-align: center; 
            color: #333;
            font-size: 28px;
            margin-bottom: 2px;
        }

        .login-section h3 {
            text-align: center; 
            color: #666;
            font-size: 18px;
            margin-bottom: 25px;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            display: block;
            text-align: left; 
            width: 100%; 
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-btn {
            background-color: #003366;
            color: white;
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: white;
        }

        .links {
            display: flex; 
            justify-content: center; 
            width: 100%; 
            margin-top: 15px;
        }

        .links a {
            color: #800020;
            text-decoration: none;
            margin-right: 15px;
            text-align: center;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .image-section {
            width: 50%;
            background: url('side_log.svg') no-repeat center center;
            background-size: cover;
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
        
        <div class="login-section">
            <h2>Login</h2>
            <h3></h3> 

            <form method="POST" action="login_process.php">
            <label>EMAIL</label>
            <input type="email" id="uname" name="uname" required>

            <label>PASSWORD</label>
            <input type="password" id="pass" name="pass" required>

                <button type="submit" class="login-btn">OK</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='index.php';">CANCEL</button>

                <div class="links">
                    <a href="member.php"><u>SIGN UP</u></a> 
                    <a href="forgotpass.php">FORGOT PASSWORD</a>
                </div>

            </form>

        </div>

   
        <div class="image-section">
          
        </div>

    </div>

        <div class="background-waves">
            <svg class="wave1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#003366" fill-opacity="1" d="M0,288L80,272C160,256,320,224,480,224C640,224,800,256,960,256C1120,256,1280,224,1360,208L1440,192V320H0Z"></path></svg>

            <svg class="wave2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#D6C4A1" fill-opacity="1" d="M0,320L80,293.3C160,267,320,213,480,192C640,171,800,181,960,186.7C1120,192,1280,192,1360,192L1440,192V320H0Z"></path></svg>

            <svg class="wave3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#F4F1E1" fill-opacity="1" d="M0,320L80,282.7C160,245,320,171,480,138.7C640,107,800,117,960,144C1120,171,1280,213,1360,234.7L1440,256V320H0Z"></path></svg>
        </div>

</body>
</html>