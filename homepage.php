<?php
    include 'db_connect.php';
    session_start();

    
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT balance FROM MEMBER_PROFILE WHERE user_id = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("i", $user_id); 
            $stmt->execute();
            $stmt->bind_result($balance);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }

        $invoices = [];
        $sqlInvoices = "SELECT invoice_id, description, amount, due_date, status FROM INVOICES WHERE user_id = ?";
        if($stmt = $conn->prepare($sqlInvoices)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $invoices[] = $row;
            }
            $stmt->close();
        }

        $notifications = [];
        $notifSql = "SELECT message, created_at FROM NOTIFICATIONS WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
        if($stmt = $conn->prepare($notifSql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
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

        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #FDFBF6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
            font-size: 16px;
            margin-top: 10px;
        }

        .invoice-table th {
            background-color: #D6C4A1;
            color: #003366;
            padding: 12px;
            text-align: center;
            font-weight: 600;
        }

        .invoice-table td {
            background-color: #fff;
            padding: 12px;
            color: #333;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-table td:nth-child(4) {
            font-weight: bold;
            text-transform: capitalize;
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

        .upload-form {
            margin-top: 20px;
            background: #FFFDF8;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #E2D2B0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .upload-form label {
            color: #003366;
            font-weight: bold;
            font-size: 16px;
        }

        .upload-form input[type="file"] {
            padding: 8px;
            background: #F4F1E1;
            border: 1px solid #D6C4A1;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        .upload-form input[type="submit"] {
            background-color: #003366;
            color: #F4F1E1;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .upload-form input[type="submit"]:hover {
            background-color: #002244;
            transform: scale(1.03);
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #ffffff;
            margin: 15% auto;
            padding: 30px 40px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            text-align: center;
            color: #003366;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .modal-content h2 {
            color: #003366;
            margin-bottom: 20px;
        }

        .modal-content p {
            font-size: 16px;
            color: #333;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 10px;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
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


<section class="hero">
    <h1>WELCOME TO UNIHUB COOPERATIVE</h1>
    <h3>Your trusted partner in financial growth and community development.</h3>
    <button class="btn" onclick="window.location.href='announcement.php';">VIEW ANNOUNCEMENTS</button>
    <div class="dots">
        <span></span><span></span><span></span>
    </div>
</section>

<main class="content">
    <div class="main-box-container">
        <div class="slider-container">
            <div class="slider-box">
                <h3>Balance: PHP <?php echo number_format($balance, 2); ?></h3> 
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
            <button class="btn" id="withdrawBtn" onclick="window.location.href='withdraw.php'">WITHDRAW</button>
        </div>
        <p>Withdraw from your account</p>
    </div>
    <div class="box">
        <div class="box-img1">
            <button class="btn" id="depositBtn" onclick="window.location.href='deposit.php'">DEPOSIT</button>
        </div>
        <p>Deposit to your account</p>
    </div>
    <div class="box">
        <div class="box-img2">
            <button class="btn" id="statementBtn">VIEW STATEMENT</button>
        </div>
        <p>Check your account statement</p>
    </div>
</div>

<div id="statementModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Statement Request Sent!</h2>
    <p>
        You requested for an account statement. <br> We will contact you shortly. <br>
        Rest assured—because in <strong>UniHub</strong>, transparency is a priority!
    </p>
  </div>
</div>

        <h1 class="section-title">Your Invoices and Payments</h1>
        <table class="invoice-table">
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
            <?php if (count($invoices) > 0): ?>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?php echo $inv['description']; ?></td>
                        <td>₱<?php echo number_format($inv['amount'], 2); ?></td>
                        <td><?php echo $inv['due_date']; ?></td>
                        <td><?php echo ucfirst($inv['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No invoices found.</td></tr>
            <?php endif; ?>
        </table>

        <h1 class="section-title">Submit Proof of Payment</h1>
        <form class="upload-form" action="upload_payment.php" method="post" enctype="multipart/form-data">
            <label for="payment_file">Choose a file (JPG, PNG, PDF):</label>
            <input type="file" name="payment_file" id="payment_file" accept=".jpg,.jpeg,.png,.pdf" required>
            <input type="submit" value="Upload Payment Proof">
        </form>

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

<script>
    const statementBtn = document.getElementById('statementBtn');
    const modal = document.getElementById('statementModal');

    statementBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    function closeModal() {
        modal.style.display = 'none';
    }

    // Optional: close modal if user clicks outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
</script>
</html>