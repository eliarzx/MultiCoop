<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch members
$members = [];
$member_query = $conn->query("SELECT member_id FROM MEMBER_PROFILE");
while ($row = $member_query->fetch_assoc()) {
    $members[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $account_balance = $_POST['account_balance'];
    $deposit_date = $_POST['deposit_date'];
    $interest_rate = $_POST['interest_rate'];
    $status = $_POST['status'];

    if (!is_numeric($account_balance) || !is_numeric($interest_rate)) {
        echo "<script>alert('Balance and interest rate must be numeric.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO SAVINGS_ACCOUNTS (member_id, account_balance, deposit_date, interest_rate, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sdsss", $member_id, $account_balance, $deposit_date, $interest_rate, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Savings account added successfully.'); window.location.href='savingsAccounts_Page.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding account.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Savings Account</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            padding: 20px;
        }

        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: white;
        }

        h2 {
            color: #FFFFFF;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            color: #FFFFFF;
            margin-bottom: 8px;
        }

        input, select {
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            background-color: #ffffff;
            font-weight: 600;
            color: #000;
            transition: all 0.2s ease;
        }

        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        button {
            font-size: 18px;
            font-weight: bold;
            width: 220px;
            height: 56px;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .add-btn {
            background-color: #F4F1E1;
            color: black;
        }

        .add-btn:hover {
            background-color: #003366;
            color: white;
            transform: scale(1.03);
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: black;
        }

        .cancel-btn:hover {
            background-color: #5e0000;
            color: white;
            transform: scale(1.03);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Savings Account</h2>
        <form method="POST">
<div class="form-group">
    <label for="member_id">Member ID:</label>
    <select name="member_id" id="member_id" required>
        <option value="" disabled selected>Select Member</option>
        <?php foreach ($members as $member): ?>
            <option value="<?= $member['member_id'] ?>"><?= htmlspecialchars($member['member_id']) ?></option>
        <?php endforeach; ?>
    </select>
</div>

            <div class="form-group">
                <label for="account_balance">Account Balance (₱):</label>
                <input type="number" step="0.01" name="account_balance" id="account_balance" required>
            </div>

            <div class="form-group">
                <label for="deposit_date">Deposit Date:</label>
                <input type="date" name="deposit_date" id="deposit_date" required>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" step="0.01" name="interest_rate" id="interest_rate" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Closed</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Add Account</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='savingsAccounts_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>