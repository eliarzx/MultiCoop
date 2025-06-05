<?php
include 'db_connect.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$balance = 0;
$invoices = [];
$notifications = [];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT balance FROM MEMBER_PROFILE WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $sqlInvoices = "SELECT invoice_id, description, amount, due_date, status FROM INVOICES WHERE user_id = ?";
    if ($stmt = $conn->prepare($sqlInvoices)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        $stmt->close();
    }

    $notifSql = "SELECT message, created_at FROM NOTIFICATIONS WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
    if ($stmt = $conn->prepare($notifSql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        $stmt->close();
    }
} else {
    echo "Please log in first.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Co-UniHub Multipurpose Cooperative</title>
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
        
        .hero h1 {
            font-size: 34px;
            margin-bottom: 2px;
        }
        
        .hero p {
            font-size: 26px;
            margin-top: 2px;
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
        }

        .hero .btn {
            background-color: transparent;
            color: #F4F1E1;
            padding: 20px 40px;
            font-size: 20px;
            font-weight: bold;
            border: 2px solid #F4F1E1;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .hero .btn:hover {
            background-color: #F4F1E1;
            color: #003366;
            transform: scale(1.05);
        }

        .dots {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .dots span {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #F4F1E1;
            margin: 5px;
            border-radius: 50%;
            animation: dotAnimation 1.5s ease-in-out infinite;
        }

        .dots span:nth-child(1) { animation-delay: 0s; }
        .dots span:nth-child(2) { animation-delay: 0.3s; }
        .dots span:nth-child(3) { animation-delay: 0.6s; }

        @keyframes dotAnimation {
            0% { transform: scale(1); }
            50% { transform: scale(1.5); }
            100% { transform: scale(1); }
        }

        main.content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 60px auto 20px auto;
        }

        .main-box-container {
            max-width: 1000px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
        }

        .slider-container {
            display: flex;
            width: 100%;
            justify-content: space-between;
            gap: 20px;
        }

        .slider-box,
        .recent-articles {
            flex: 1;
            background: #F4F1E1;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            height: 200px;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .slider-box h3 {
            font-size: 24px;
            color: #003366;
            text-align: center;
        }

        .recent-articles h3 {
            margin-top: 0;
        }

        .recent-articles ul {
            list-style-type: none;
            padding-left: 0;
        }

        .recent-articles li {
            margin-bottom: 10px;
            color: #003366;
            transition: color 0.3s ease;
        }

        .recent-articles li:hover {
            color: #800020;
        }

        .info-boxes {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
        }

        .box {
            flex: 1;
            background: #F4F1E1;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .box-img, .box-img1, .box-img2 {
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .box-img { background: #B0B0B0; }
        .box-img1 { background: #D6C4A1; }
        .box-img2 { background: #f4f4f4; }

        .box-img:hover, .box-img1:hover, .box-img2:hover {
            background-color: #003366;
        }

        .box button {
            background-color: transparent;
            color: #003366;
            font-size: 24px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            width: 100%;
            height: 100%;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .box button:hover {
            color: #F4F1E1;
            transform: scale(1.05);
        }

        .box p {
            padding: 10px;
        }

        .text-section {
            width: 100vw;
            max-width: 1000px;
            background: #F4F1E1;
            padding: 20px;
            margin-top: 40px;
            box-sizing: border-box;
        }

        .text-section h2 {
            color: #003366;
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

        .section-title {
            font-size: 22px;
            color: #003366;
            margin-top: 40px;
            margin-bottom: 10px;
        }

        .notification-box {
            background: #FFF8E6;
            border-left: 6px solid #D6C4A1;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .notification-box h3 {
            color: #003366;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .notification-box p {
            color: #5A4630;
            font-size: 16px;
            margin: 6px 0;
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


<section class="hero">
    <h1>WELCOME TO UNIHUB COOPERATIVE</h1>
    <h3>Your trusted partner in financial growth and community development.</h3>
    <button class="btn" onclick="window.location.href='announcement2.php';">VIEW ANNOUNCEMENTS</button>
    <div class="dots">
        <span></span><span></span><span></span>
    </div>
</section>

<main class="content">
    <div class="main-box-container">
        <div class="slider-container">
            <div class="slider-box">
                <h3>Remaining Loan Balance:</br> PHP <?php echo number_format($balance, 2); ?></h3> 
            </div>
            <aside class="recent-articles">
                <h2>Notifications</h2>
                <ul>
                    <?php if (count($notifications) > 0): ?>
                        <?php foreach ($notifications as $note): ?>
                            <li>➤ <?php echo $note['message']; ?> <small>(<?php echo date('M d, Y', strtotime($note['created_at'])); ?>)</small></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No new notifications.</li>
                    <?php endif; ?>
                </ul>
            </aside>
        </div>


        <div class="info-boxes">
            <div class="box">
                <div class="box-img">
                    <button class="btn" id="withdrawBtn" onclick="window.location.href='loan_ApplicationH.php'">LOAN APPLICATION</button>
                </div>
                <p>Check your pending loan application or apply for one!</p>
            </div>
            <div class="box">
                <div class="box-img1">
                    <button class="btn" id="depositBtn" onclick="window.location.href='loan_Repay.php'">LOAN REPAYMENT</button>
                </div>
                <p>Pay your loans on time and see your repayment history.</p>
            </div>
        </div>


        <section class="text-section">
            <h2>About Co-UniHub Cooperative</h2>
            <p>Co-UniHub Multipurpose Cooperative is committed to serving its members through financial services that empower individuals and communities. With offerings like savings, time deposits, and loan services, we help you grow financially and achieve your goals.</p>
        </section>
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