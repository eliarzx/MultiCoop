<?php
include 'db_connect.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect if no user session
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

// Redirect if no ID in URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No account ID provided.'); window.location.href='timeDepositAccounts_Page.php';</script>";
    exit();
}

$account_id = $_GET['id'];

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $account_balance = $_POST['account_balance'];
    $deposit_date = $_POST['deposit_date'];
    $maturity_date = $_POST['maturity_date'];
    $interest_rate = $_POST['interest_rate'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE TIME_DEPOSIT_ACCOUNTS SET member_id=?, account_balance=?, deposit_date=?, maturity_date=?, interest_rate=?, status=? WHERE account_id=?");
    $stmt->bind_param("idssdii", $member_id, $account_balance, $deposit_date, $maturity_date, $interest_rate, $status, $account_id);

    if ($stmt->execute()) {
        echo "<script>alert('Time deposit account updated successfully.'); window.location.href='timeDepositAccounts_Page.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update account.');</script>";
    }
}

// Fetch account data
$stmt = $conn->prepare("SELECT * FROM TIME_DEPOSIT_ACCOUNTS WHERE account_id = ?");
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();
$account = $result->fetch_assoc();

if (!$account) {
    echo "<script>alert('Account not found.'); window.location.href='timeDepositAccounts_Page.php';</script>";
    exit();
}

// Fetch members for dropdown
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
    <title>Edit Time Deposit Account</title>
    <style>
        /* Same styling as previous forms */
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
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: white;
        }

        h2 {
            text-align: center;
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
            margin-bottom: 8px;
        }

        input, select {
            padding: 14px;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            background-color: #fff;
            color: #000;
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
        }

        .update-btn {
            background-color: #F4F1E1;
            color: black;
        }

        .update-btn:hover {
            background-color: #003366;
            color: white;
        }

        .cancel-btn {
            background-color: #D6C4A1;
            color: black;
        }

        .cancel-btn:hover {
            background-color: #5e0000;
            color: white;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Time Deposit Account</h2>
    <form method="POST">
        <div class="form-group">
            <label for="member_id">Select Member:</label>
            <select name="member_id" id="member_id" required>
                <?php foreach ($members as $member): ?>
                    <option value="<?= $member['member_id'] ?>" <?= ($member['member_id'] == $account['member_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($member['member_id']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="account_balance">Account Balance (₱):</label>
            <input type="number" step="0.01" name="account_balance" id="account_balance" value="<?= $account['account_balance'] ?>" required>
        </div>

        <div class="form-group">
            <label for="deposit_date">Deposit Date:</label>
            <input type="date" name="deposit_date" id="deposit_date" value="<?= $account['deposit_date'] ?>" required>
        </div>

        <div class="form-group">
            <label for="maturity_date">Maturity Date:</label>
            <input type="date" name="maturity_date" id="maturity_date" value="<?= $account['maturity_date'] ?>" required>
        </div>

        <div class="form-group">
            <label for="interest_rate">Interest Rate (%):</label>
            <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="<?= $account['interest_rate'] ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="1" <?= $account['status'] == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= $account['status'] == 2 ? 'selected' : '' ?>>Matured</option>
                <option value="0" <?= $account['status'] == 3 ? 'selected' : '' ?>>Closed</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="update-btn">Update Account</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='timeDepositAccounts_Page.php'">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>