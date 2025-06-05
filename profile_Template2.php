<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        U.first_name, U.middle_name, U.last_name, U.birthdate, U.sex,
        U.barangay, U.municipality_code, U.province,
        M.account_type, A.email
    FROM USERS U
    LEFT JOIN MEMBER_PROFILE M ON U.user_id = M.user_id
    LEFT JOIN USER_ACCOUNT A ON U.user_id = A.user_id
    WHERE U.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Error: User data not found.";
    exit();
}

$birthdate = new DateTime($user['birthdate']);
$today = new DateTime();
$age = $today->diff($birthdate)->y;

function safeDisplay($value) {
    return $value ? htmlspecialchars($value) : "<i>You haven’t set it up yet. <a href='edit_User.php'>Set up now</a>.</i>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: white;
        }

        .profile {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            width: 1000px;
            height: auto;
            display: flex;
            background: linear-gradient(to bottom, #F4F1E1, white);
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            transition: transform 0.3s ease;
        }


        .left-section {
            width: 320px;
            background: #003366;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            position: relative;
        }

        .profile-card {
            width: 280px;
            height: 290px;
            position: absolute;
            top: 50%;
            left: 280px;
            transform: translateY(-50%);
            background: #f4f4f4;
            box-shadow: -5px 5px 20px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .id {
            font-size: 24px;
            font-weight: bold;
            color: #800020;
            margin-top: 10px;
        }

        .profile-card:hover {
            box-shadow: -5px 5px 30px rgba(0, 0, 0, 0.3);
        }

        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            background-color: #B0B0B0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 36px;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .profile-img:hover {
            background-color: #800020;
        }

        .name {
            font-size: 19px;
            font-weight: bold;
            color: #003366;
            margin: 2px 0;
        }

        .id {
            font-size: 14px;
            font-weight: 100;
            color: #800020;
        }

        .logo {
            width: 100px;
            height: 50px;
            margin-top: 10px;
        }

        .right-section {
            flex: 1;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
        }

        .section-title {
            font-size: 38px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
            padding-left: 100px;
        }
        
        .profile-card .id {
            font-size: 18px;
            font-weight: bold;
        }

        .info {
            font-size: 18px;
            color: #003366;
            margin-bottom: -6px;
            padding-left: 80px;
            line-height: 1.4;
        }

        .info span {
            font-weight: bold;
            color: #003366;
        }

        .contact-button {
            width: 280px;
            height: 60px;
            border-radius: 10px;
            background: #003366;
            color: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 70px;
            margin-left: 210px;
            transition: background 0.3s, color 0.3s;
        }

        .contact-button:hover {
            background: #800020;
            color: #F4F1E1;
        }


    </style>
</head>
<body>

    <ul class="navbar">
        <li><a href="loanAccount.php">HOME</a></li>
        <li><a href="profile_Template2.php">MY PROFILE</a></li>
        <li><a href="#notifications">NOTIFICATION</a></li>
        <li><a href="helpMembs2.php">HELP</a></li>
        <li><a href="index.php">LOG OUT</a></li>
    </ul>
    
    <div class="profile">
        <div class="profile-container">
            
            <div class="left-section"></div>
            
            <div class="profile-card">
                <div class="profile-img">
                    <span><?php echo strtoupper($user['first_name'][0] . $user['last_name'][0]); ?></span>
                </div>
                <div class="name"><?php echo strtoupper(htmlspecialchars($user['first_name'])); ?></div>
                <div class="name"><?php echo strtoupper(htmlspecialchars($user['last_name'])); ?></div>
                <div class="id">Account Type: <?php echo htmlspecialchars($user['account_type']); ?></div>
                <div class="divider"></div>
                <img class="logo" src="icons.svg" alt="Logo">
            </div>

            <div class="right-section">
                <div class="section-title">Personal Information</div>
                <div class="info-divider"></div>
                <p class="info"><span>First Name:</span> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p class="info"><span>Last Name:</span> <?php echo htmlspecialchars($user['last_name']); ?></p>
                <p class="info"><span>Gender:</span> <?php echo htmlspecialchars($user['sex']); ?></p>
                <p class="info"><span>Birthdate:</span> <?php echo htmlspecialchars($user['birthdate']); ?></p>
                <p class="info"><span>Age:</span> <?php echo $age; ?> years old</p>
                <p class="info"><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></p> 
                <p class="info"><span>Barangay:</span> <?php echo safeDisplay($user['barangay']); ?></p>
                <p class="info"><span>Municipality Code:</span> <?php echo safeDisplay($user['municipality_code']); ?></p>
                <p class="info"><span>Province:</span> <?php echo safeDisplay($user['province']); ?></p>
                
                <div class="contact-button" onclick="window.location.href='edit_User.php';">Update Profile</div>    
            </div>
        </div>
    </div>

</body>
</html>