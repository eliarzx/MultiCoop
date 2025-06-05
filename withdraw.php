<?php
include 'db_connect.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";
$show_receipt = false;
$amount = 0;
$ref = strtoupper(uniqid('WD-'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = floatval($_POST['amount']);

    if ($amount > 0) {
        $check = $conn->prepare("SELECT balance FROM MEMBER_PROFILE WHERE user_id = ?");
        $check->bind_param("i", $user_id);
        $check->execute();
        $check->bind_result($current_balance);
        $check->fetch();
        $check->close();

        if ($current_balance >= $amount) {
            $stmt = $conn->prepare("UPDATE MEMBER_PROFILE SET balance = balance - ? WHERE user_id = ?");
            $stmt->bind_param("di", $amount, $user_id);
        if ($stmt->execute()) {
            $success = "Successfully withdrew PHP " . number_format($amount, 2) . "!";
            $show_receipt = true;

            $notif = $conn->prepare("INSERT INTO NOTIFICATIONS (user_id, message) VALUES (?, ?)");
            $message = "You withdrew PHP " . number_format($amount, 2) . " on " . date("F d, Y h:i A");
            $notif->bind_param("is", $user_id, $message);
            $notif->execute();
            $notif->close();
        }else {
            $error = "Withdrawal failed. Please try again.";
        }
            $stmt->close();
        } else {
            $error = "Insufficient balance.";
        }
    } else {
        $error = "Enter a valid withdrawal amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw | Co-UniHub</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #D6C4A1, #F4F1E1);
            background-size: 400% 400%;
            animation: animateBG 15s ease infinite;
            margin: 0;
        }

        @keyframes animateBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            margin-top: 190px;
            background: #fff;
            max-width: 690px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #003366;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .info {
            background-color: #F4F1E1;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 16px;
            color: #333;
        }

        input[type="number"] {
            padding: 16px;
            width: 90%;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .btn-large {
            padding: 16px 32px;
            background-color: #003366;
            color: #F4F1E1;
            font-size: 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 200px;
            height: 60px;
        }

        .btn-large:hover {
            background-color: #800020;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            font-size: 16px;
            border-radius: 6px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        #receipt {
            margin-top: 30px;
            text-align: left;
            background: #fdfdfd;
            padding: 20px;
            border: 1px dashed #003366;
            font-family: monospace;
            line-height: 1.6;
        }

        #receipt pre {
            font-size: 14px;
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>
</head>
<body>

<ul class="navbar">
    <li><a href="homepage.php">HOME</a></li>
    <li><a href="profile_Template.php">MY PROFILE</a></li>
    <li><a href="#notifications">NOTIFICATION</a></li>
    <li><a href="helpMembs.php">HELP</a></li>
    <li><a href="index.php">LOG OUT</a></li>
</ul>

<div class="container">
    <h2>Withdraw Funds</h2>
    <div class="info">
        Enter the amount you wish to withdraw from your account.
    </div>
    <form method="POST" action="">
        <input type="number" name="amount" placeholder="Enter amount in PHP" step="0.01" required><br>
        <div class="button-group">
            <button type="submit" class="btn-large">WITHDRAW</button>
            <button type="button" class="btn-large" onclick="window.location.href='homepage.php'">HOMEPAGE</button>
        </div>
    </form>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($show_receipt): ?>
    <div id="receipt">
        <pre>
════════════════════════════════════════════════════
                  UNIHUB COOPERATIVE
                   Malolos, Bulacan
                         2025
════════════════════════════════════════════════════
             OFFICIAL TRANSACTION RECEIPT
════════════════════════════════════════════════════
Reference No:     <?php echo $ref; ?>         
Date & Time:      <?php echo date("F d, Y h:i A"); ?>      
Transaction:      WITHDRAW
Amount:           PHP <?php echo number_format($amount, 2); ?>
  
User ID:          <?php echo $user_id; ?>             
Processed by:     SYSTEM (AUTO)
Remarks:          Successful withdrawal

════════════════════════════════════════════════════
        Thank you for banking with UniHub Coop!
════════════════════════════════════════════════════
        </pre>
        <button onclick="printReceipt()" class="btn-large">Print Receipt</button>
    </div>

    <script>
        function printReceipt() {
            var content = document.getElementById('receipt').innerHTML;
            var original = document.body.innerHTML;
            document.body.innerHTML = content;
            window.print();
            document.body.innerHTML = original;
            location.reload();
        }
    </script>
    <?php endif; ?>
</div>

</body>
</html>