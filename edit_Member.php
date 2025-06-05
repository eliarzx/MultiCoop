<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['member_id'])) {
    echo "<script>alert('No member ID provided.'); window.location.href='member_Page.php';</script>";
    exit();
}

$member_id = intval($_GET['member_id']);
$stmt = $conn->prepare("SELECT * FROM MEMBER_PROFILE WHERE member_id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('Member not found.'); window.location.href='member_Page.php';</script>";
    exit();
}

$member = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_type = $_POST['account_type'];
    $monthly_salary = $_POST['monthly_salary'];
    $net_income = $_POST['net_income'];
    $balance = $_POST['balance'];
    $wants_to_open_account = 1; // Hidden from user but always set to true

    $update_stmt = $conn->prepare("UPDATE MEMBER_PROFILE SET account_type = ?, monthly_salary = ?, net_income = ?, Balance = ?, wants_to_open_account = ? WHERE member_id = ?");
    $update_stmt->bind_param("sddiii", $account_type, $monthly_salary, $net_income, $balance, $wants_to_open_account, $member_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Member updated successfully.'); window.location.href='member_Page.php';</script>";
    } else {
        echo "<script>alert('Update failed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #001f3f;
        padding: 20px;
    }

    .form-container {
        max-width: 1000px;
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 50px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        color: white;
    }

    h2 {
        color: #FFFFFF;
        text-align: center;
        margin-bottom: 20px;
        font-size: 32px;
    }

    form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-group {
        flex: 1 1 45%;
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
        height: 28px;
        border: none;
        border-radius: 6px;
        background-color: #ffffff;
        font-weight: 600;
        color: #000;
        transition: all 0.2s ease;
        appearance: none;
    }

    select {
        padding: 14px;
        font-size: 18px;
        height: 56px;
    }

    input:focus, select:focus {
        outline: none;
        box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
        background-color: #ffffff;
    }

    .form-actions {
        flex-basis: 100%;
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 40px;
    }

    button {
        font-size: 18px;
        font-weight: bold;
        width: 300px;
        height: 58px;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .update-btn {
        background-color: #F4F1E1;
        color: black;
    }

    .update-btn:hover {
        background-color: #003366;
        transform: scale(1.03);
    }

    .cancel-btn {
        background-color: #D6C4A1;
        color: black;
    }

    .cancel-btn:hover {
        background-color: #5e0000;
        transform: scale(1.03);
    }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Member</h2>
    <form method="POST">
        <div class="form-group">
            <label>Account Type</label>
            <select name="account_type" required>
                <option value="savings" <?= $member['account_type'] == 'savings' ? 'selected' : '' ?>>Savings</option>
                <option value="time_deposit" <?= $member['account_type'] == 'time_deposit' ? 'selected' : '' ?>>Time Deposit</option>
                <option value="fixed_account" <?= $member['account_type'] == 'fixed_account' ? 'selected' : '' ?>>Fixed Account</option>
            </select>
        </div>

        <div class="form-group">
            <label>Monthly Salary (₱)</label>
            <input type="number" name="monthly_salary" step="0.01" value="<?= $member['monthly_salary'] ?>" required />
        </div>

        <div class="form-group">
            <label>Net Income (₱)</label>
            <input type="number" name="net_income" step="0.01" value="<?= $member['net_income'] ?>" required />
        </div>

        <div class="form-group">
            <label>Balance (₱)</label>
            <input type="number" name="balance" step="0.01" value="<?= $member['Balance'] ?>" required />
        </div>

        <div class="form-actions">
            <button type="submit" class="update-btn">Update</button>
            <a href="member_Page.php"><button type="button" class="cancel-btn">Cancel</button></a>
        </div>
    </form>
</div>
</body>
</html>