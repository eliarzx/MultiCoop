<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $account_balance = $_POST['account_balance'];
    $deposit_date = $_POST['deposit_date'];
    $maturity_date = $_POST['maturity_date'];
    $interest_rate = $_POST['interest_rate'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO TIME_DEPOSIT_ACCOUNTS (member_id, account_balance, deposit_date, maturity_date, interest_rate, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idssdi", $member_id, $account_balance, $deposit_date, $maturity_date, $interest_rate, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Time deposit account added successfully.'); window.location.href='timeDepositAccounts_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to add time deposit account.');</script>";
    }
}

$members_stmt = $conn->prepare("SELECT member_id FROM MEMBER_PROFILE");
$members_stmt->execute();
$members_result = $members_stmt->get_result();
$members = [];
while ($row = $members_result->fetch_assoc()) {
    $members[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Time Deposit Account</title>
    <style>
        /* Your existing CSS styles here (same as in fixed version) */
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
        <h2>Add Time Deposit Account</h2>
        <form method="POST">
            <div class="form-group">
                <label for="member_id">Select Member:</label>
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
                <label for="maturity_date">Maturity Date:</label>
                <input type="date" name="maturity_date" id="maturity_date" required>
            </div>

            <div class="form-group">
                <label for="interest_rate">Interest Rate (%):</label>
                <input type="number" step="0.01" name="interest_rate" id="interest_rate" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="1">Active</option>
                    <option value="0">Matured</option>
                    <option value="0">Closed</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="add-btn">Add Time Deposit</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='timeDepositAccounts_Page.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>