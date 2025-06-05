<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'db_connect.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    $checkSql = "SELECT user_id FROM USERS WHERE first_name = ? AND last_name = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $firstName, $lastName);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        $errorMessage = "Please ask assistance from customer service for account registration.";
    } else {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id']; 
        $userId = $row['user_id'];

        $accountType = $_POST['account_type'];
        $monthlySalary = $_POST['monthly_salary'];
        $netIncome = $_POST['net_income'];
        $wantsToOpenAccount = isset($_POST['wants_to_open_account']) ? 1 : 0;

        $sql = "INSERT INTO MEMBER_PROFILE (user_id, account_type, Balance, monthly_salary, net_income, wants_to_open_account)
                VALUES (?, ?, 0, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdi", $userId, $accountType, $monthlySalary, $netIncome, $wantsToOpenAccount);

        if ($stmt->execute()) {
            $memberId = $conn->insert_id;
            $accountType = strtolower($accountType);
            $status = 'active';
            $createdAt = date('Y-m-d');
            $maturityDate = null;
            $interestRate = 0;
            $balance = 0;

            if ($accountType === 'savings') {
                $interestRate = 0.01;
                $insertAcc = "INSERT INTO SAVINGS_ACCOUNTS (member_id, account_balance, deposit_date, interest_rate, status, created_at)
                              VALUES (?, ?, ?, ?, ?, ?)";
                $stmtAcc = $conn->prepare($insertAcc);
                $stmtAcc->bind_param("idsdss", $memberId, $balance, $createdAt, $interestRate, $status, $createdAt);

            } elseif ($accountType === 'time_deposit') {
                $interestRate = 0.04;
                $maturityDate = date('Y-m-d', strtotime('+6 months'));
                $insertAcc = "INSERT INTO TIME_DEPOSIT_ACCOUNTS (member_id, account_balance, deposit_date, maturity_date, interest_rate, status, created_at)
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtAcc = $conn->prepare($insertAcc);
                $stmtAcc->bind_param("idssdss", $memberId, $balance, $createdAt, $maturityDate, $interestRate, $status, $createdAt);

            } elseif ($accountType === 'fixed_account') {
                $interestRate = 0.06;
                $maturityDate = date('Y-m-d', strtotime('+12 months'));
                $insertAcc = "INSERT INTO FIXED_ACCOUNTS (member_id, account_balance, deposit_date, maturity_date, interest_rate, status, created_at)
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtAcc = $conn->prepare($insertAcc);
                $stmtAcc->bind_param("idssdss", $memberId, $balance, $createdAt, $maturityDate, $interestRate, $status, $createdAt);

            } elseif ($accountType === 'loan') {
                $loanTypeId = 1;
                $insertAcc = "INSERT INTO LOAN_ACCOUNTS (member_id, loan_type_id, balance, status, created_at)
                              VALUES (?, ?, ?, ?, ?)";
                $stmtAcc = $conn->prepare($insertAcc);
                $stmtAcc->bind_param("iiiss", $memberId, $loanTypeId, $balance, $status, $createdAt);
            }

            if ($stmtAcc && $stmtAcc->execute()) {
                $stmtAcc->close();
                $successMessage = "Thank you for signing up. Welcome ka-UniHub!";
            } else {
                $errorMessage = "Error inserting into account table: " . ($stmtAcc ? $stmtAcc->error : 'Invalid account type');
            }
        } else {
            $errorMessage = "Error inserting into MEMBER_PROFILE table: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Profile</title>
    <link rel="stylesheet" href="nav.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
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
            0% { transform: translateY(0); }
            25% { transform: translateY(-10px); }
            50% { transform: translateY(0); }
            75% { transform: translateY(10px); }
            100% { transform: translateY(0); }
        }

        .wave2 {
            opacity: 0.4;
            animation: waveAnimation 12s ease-in-out infinite;
        }

        .wave3 {
            opacity: 0.2;
            animation: waveAnimation 14s ease-in-out infinite;
        }

        .main-container {
            display: flex;
            height: 80vh;
            margin-top: 90px;
            width: 80%;
            max-width: 1200px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-left: auto;
            margin-right: auto;
        }

        .image-section {
            width: 50%;
            height: 100%;
            overflow: hidden;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .registration {
            width: 55%;
            padding: 40px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            margin-left: -4%;
        }

        h2 {
            text-align: center;
            color: #003366;
            font-size: 32px;
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
            display: block;
            margin-left: 10px;
        }

        .form-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.95);
            color: black;
            outline: none;
            cursor: pointer;
            appearance: none;
        }

        .form-group select:focus {
            border: 1px solid #003366;
            background: white;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 8px;
            margin-top: 5px;
        }

        .submit-btn {
            background-color: #003366;
            color: white;
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: white;
        }

        .form-group input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .radio-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-top: 10px;
        }

        .radio-container label {
            font-size: 18px;
        }

        .navbar li a {
            font-size: 18px;
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

    <div class="main-container">
        <div class="image-section">
            <img src="signupside.svg" alt="Side Image">
        </div>

        <div class="registration">
            <h2>MEMBERSHIP</h2>
            <div class="form-container">

    <form action="member.php" method="POST">
        <div class="form-group">
            <label for="first_name">Enter Your First Name</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <div class="form-group">
            <label for="last_name">Enter Your Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="account_type">Account Type</label>
            <select name="account_type" id="account_type" required>
                <option value="">Select Account Type</option>
                <option value="savings">Savings</option>
                <option value="time_deposit">Time Deposit</option>
                <option value="fixed_account">Fixed Account</option>
                <option value="loan">Loan Account</option> <!-- Added loan option -->
            </select>
        </div>

        <div class="form-group">
            <label for="monthly_salary">Monthly Salary</label>
            <input type="number" id="monthly_salary" name="monthly_salary" step="0.01" placeholder="Enter Monthly Salary" required>
        </div>

        <div class="form-group">
            <label for="net_income">Net Income</label>
            <input type="number" id="net_income" name="net_income" step="0.01" placeholder="Enter Net Income" required>
        </div>

        <div class="checkbox-container">
            <input type="checkbox" id="wants_to_open_account" name="wants_to_open_account">
            <label for="wants_to_open_account" style="font-size: 16px; color: #003366;">I agree to terms and conditions.</label>
        </div>

        <div class="button-container">
            <button type="submit" class="submit-btn">Submit</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='index.php';">Cancel</button>
        </div>
    </form>

        </div>

        <?php if (!empty($successMessage)): ?>
            <div style="color: green; text-align: center; margin-bottom: 10px;"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div style="color: red; text-align: center; margin-bottom: 10px;"><?php echo $errorMessage; ?></div>
        <?php endif; ?>


        <div class="background-waves">
            <svg class="wave1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#D6C4A1" d="M0,288L80,272C160,256,320,224,480,224C640,224,800,256,960,256C1120,256,1280,224,1360,208L1440,192V320H0Z"></path></svg>
            <svg class="wave2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#B0B0B0" d="M0,320L80,293.3C160,267,320,213,480,192C640,171,800,181,960,186.7C1120,192,1280,192,1360,192L1440,192V320H0Z"></path></svg>
            <svg class="wave3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#800020" d="M0,320L80,282.7C160,245,320,171,480,138.7C640,107,800,117,960,144C1120,171,1280,213,1360,234.7L1440,256V320H0Z"></path></svg>
        </div>
    </div>

</body>
</html>
